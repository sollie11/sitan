<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use File;
use Redirect;

class ClientsController extends Controller{

public function __construct(){
	$this->middleware('auth');
}

//==========================
public function newclientsimport(){
	$oData = array(
		'aEmailForms' => DB::select('SELECT * FROM newclients_emailforms'),
	);
	return view('clients.import')->with('oData', $oData);
}

//==========================
public function newclientsimportsave(Request $request){
	$aNC = DB::select('SELECT * FROM new_clients');
	$sTime = date('Y-m-d H:i:s', strtotime('now'));
	$iTotal = 0;
	$iDeleted = 0;
	foreach($aNC as $oNC){
		$oSQL = json_decode($oNC->insert_sql, 1);
		$iID = DB::select('SELECT id FROM users WHERE email = ?',
			array($oSQL['aP'][2]));
		if (isset($iID[0])){
			$iID = $iID[0]->id;
			DB::update('UPDATE users SET email = ?, deleted_at = ? WHERE id = ?',
				array('', $sTime, $iID));
		} else {
			$iID = 0;
		}
		DB::insert($oSQL['sSQL'], $oSQL['aP']);
		$iNewID = DB::getPDO()->lastInsertID();
		$iTotal++;
		if ($iID){
			DB::update('UPDATE users SET email = ?, deleted_at = ? WHERE id = ?',
				array($iNewID, $sTime, $iID));
			$iDeleted++;
		}
	}
	$iNumClients = DB::select('SELECT id FROM users WHERE is_client = ? AND ' .
		'deleted_at IS NULL', array(1));
	$oData = array(
		'iTotal' => $iTotal,
		'iDeleted' => $iDeleted,
		'sSingle' => 'clients',
		'sCaps' => 'Clients',
		'iNumClients' => sizeof($iNumClients),
	);
	$request->session()->flash('success', 'The clients have been imported.');
	return view('clients.imported')->with('oData', $oData);
//	return back();
}

public function upload(){
	$oData = array('sAction' => 'clients',
		'sSingle' => 'clients',
		'sCaps' => 'Clients',
	);
	return view('upload/upload')->with('oData', $oData);
}


//==========================
public function uploaded(){
	$sCategory = 'clients';
	$oData = array('sAction' => $sCategory);
	switch ($sCategory) {
		case 'clients':
			$oData['sSingle'] = 'clients';
			$oData['sCaps'] = 'Clients';
			$oData['iNumRecords'] = sizeof
			(DB::select('SELECT id FROM new_clients'));
			break;
		case 'questions':
			$oData['sSingle'] = 'questions';
			$oData['sCaps'] = 'Questions';
			$oData['iNumRecords'] = sizeof
			(DB::select('SELECT id FROM new_questions'));
			break;
	}
	return view('upload.done')->with('oData', $oData);
}

//==========================
public function uploadedinsert($sAction){
	error_log('uploadedinsert '.$sAction);
	$sDate = date('Y-m-d H:i:s', strtotime("now"));
	switch ($sAction){
		case 'clients':
			
			$aNewClients = DB::select('SELECT id, programme, questionnaire, ' .
					'business_name, first_name, ' .
					'surname, email FROM new_clients');
			foreach ($aNewClients as $oNC){
				$iJ = rand(100000, 500000);
				for ($iI = 0; $iI < $iJ; $iI++){
					$sTime = microtime(1);
					$sTime = $sTime - intval($sTime);
					if ($sTime < 0.1){
						$sTime += 0.1;
					}
					$sTime *= 10000;
					$sTime = substr($sTime . '1234', 0, 4);
				}
				//            $sSQL = 'DELETE FROM users WHERE email = ?';
				//            $aP = array($oNC->email);
				//            DB::delete($sSQL, $aP);
				$bFound = 1;
				while ($bFound){
					$sUsername = explode("@", $oNC->email)[0] .
					rand(1000, 9999);
					$sSQL = 'SELECT id FROM users WHERE username = ?';
					$aP = array($sUsername);
					$aID = DB::select($sSQL, $aP);
					if (!isset($aID[0])){
						$bFound = 0;
					}
				}
				$sEmailAddress = $oNC->email;
				$iProgramme = $oNC->programme;
				if (filter_var($iProgramme, FILTER_VALIDATE_INT) === false){
					$iProgramme = DB::select('SELECT id FROM programmes ' .
							'WHERE description = ?', array($iProgramme));
					if (isset($iProgramme[0])){
						$iProgramme = $iProgramme[0]->id;
					} else {
						$iProgramme = 1;
					}
				}
				
				$iQuestionnaire = $oNC->questionnaire;
				
				if (filter_var($iQuestionnaire, FILTER_VALIDATE_INT) === false){
					$iQuestionnaire = DB::select('SELECT id FROM questionnaires ' .
							'WHERE description = ?', array($iQuestionnaire));
					if (isset($iQuestionnaire[0])){
						$iQuestionnaire = $iQuestionnaire[0]->id;
					} else {
						$iQuestionnaire = 1;
					}
				}
				$sPassword = strtolower(substr($oNC->business_name, 0, 2) .
						substr($oNC->email, 0, 4) .
						substr($oNC->surname, 0, 2) . $sTime);
				$sPasswordHash = Hash::make($sPassword);
				$sSQL = 'INSERT INTO users (name, surname, email, username, ' .
						'password, programme_id, active_questionnaire_id, ' .
						'business_name, is_client, ' .
						'created_at, updated_at, email_verified_at) VALUES (' .
						'?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				$aP = array($oNC->first_name, $oNC->surname,
						$sEmailAddress, $sUsername, $sPasswordHash,
						$iProgramme, $iQuestionnaire, $oNC->business_name, 1,
						$sDate, $sDate, $sDate);
				$oSQL = array('sSQL' => $sSQL, 'aP' => $aP);
				
				$sSQL = 'UPDATE new_clients SET clear_password = ?, ' .
						'insert_sql = ?, updated_at = ? WHERE ' .
						'id = ?';
				$aP = array($sPassword, json_encode($oSQL, 1), $sDate, $oNC->id);
				DB::update($sSQL, $aP);
			}
			break;
			
		case 'questions':
			$iCat = 1;
			$iIndex = 0;
			$iIndexOpt = 1;
			$sDesc = '';
			$aNewCats = array();
			$iCatIndex = 1;
			$aNewQs = DB::select('SELECT id, category, question, ' .
					'options, tooltip, score FROM new_questions');
			foreach ($aNewQs as $oNQ){
				
				$sSQL = 'INSERT INTO questions (category_id, index_no, ' .
						'description, tooltip, ' .
						'created_at, updated_at) VALUES (' .
						'?, ?, ?, ?, ?, ?)';
				if ($oNQ->category){
					$iCat = DB::select('SELECT id FROM questions_categories ' .
							'WHERE description = ?', array($oNQ->category));
					if (isset($iCat[0])){
						$iCat = $iCat[0]->id;
					} else {
						DB::insert('INSERT INTO questions_categories ' .
								'(description, index_no, created_at, updated_at) ' .
								'VALUES (?, ?, ?, ?)',
								array($oNQ->category, $iCatIndex, $sDate, $sDate)
								);
						$iCatIndex++;
						$iCat = DB::getPdo()->lastInsertId();
						$aNewCats[] = $iCat;
					}
				}
				$bInsQ = 0;
				if ($oNQ->question){
					$sDesc = $oNQ->question;
					$iIndex++;
					$bInsQ = 1;
					
				}
				$aP = array($iCat, $iIndex, $sDesc,
						$oNQ->tooltip, $sDate, $sDate);
				$oSQL = array('sSQL' => $sSQL, 'aP' => $aP);
				if ($bInsQ){
					$sSQL = 'UPDATE new_questions SET ' .
							'insert_sql = ?, updated_at = ? WHERE ' .
							'id = ?';
					$aP = array(json_encode($oSQL, 1), $sDate, $oNQ->id);
					DB::update($sSQL, $aP);
					$iIndexOpt = 1;
				}
				$sSQL = 'INSERT INTO questions_options ' .
						'(question_id, index_no, score, description, ' .
						'created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)';
				$aP = array(1, $iIndexOpt,  $oNQ->score, $oNQ->options, $sDate, $sDate);
				$oSQL = array('sSQL' => $sSQL, 'aP' => $aP);
				$sSQL = 'UPDATE new_questions SET ' .
						'insert_sql2 = ?, updated_at = ? WHERE ' .
						'id = ?';
				$aP = array(json_encode($oSQL, 1), $sDate, $oNQ->id);
				DB::update($sSQL, $aP);
				$iIndexOpt++;
			}
			break;
	}
}
//==========================
public function uploadnew($sRequest){
	$oRequest = json_decode(base64_decode($sRequest), 1);
	$oRequest['sAction'] = $oRequest['sPage'] . 'xlsx';
	
	$sDate = date('Y-m-d H:i:s', strtotime('now'));
	$sSQL = 'INSERT INTO uploads (created_at, data) VALUES (?, ?)';
	$aP = array($sDate, json_encode($oRequest, 1));
	DB::insert($sSQL, $aP);
	$iUploadID = DB::getPDO()->lastInsertID();
	$oRequest = 'SELECT data FROM uploads WHERE id = ?';
	$aP = array($iUploadID);
	$oRequest = DB::select($oRequest, $aP);
	if (!isset($oRequest[0])) {
		return view('upload/error')->with
		('oData', array('Upload Error, please try again'));
	}
	$oRequest = $oRequest[0]->data;
	$oRequest = json_decode($oRequest, 1);
	$oRequest['iID'] = $iUploadID;
	$sAction = $oRequest['sPage'];
	$oRules = Config::get('app.upload')[$sAction];
	$aSheet = new SpreadSheetUpload($oRequest, $oRules);
	if ($aSheet->oError['iError'] == 0) {
		$this->uploadedinsert($sAction);
		return Redirect::route('clients-uploaded', [$sAction]);
	} else {
		return Redirect::route('upload/error', [$iUploadID]);
	}
}



}


class SpreadSheetUpload
{
	public $oRequest;
	public $oError;
	public $aSheet;
	public $aFields;
	public $oOptions;
	
	//==========================
	public function __construct($oRequest, $oRules) {
		$this->aFields = $oRules['aFields'];
		$this->oOptions = $oRules['oOptions'];
		$this->oRequest = $oRequest;
		$this->oError = $this->validate();
		if (!$this->oError['iError']){
			$this->oError = $this->readdata();
			if ($this->oError['iError']){
				$sSQL = 'UPDATE uploads SET error = ? WHERE id = ?';
				$aP = array(json_encode($this->oError, 1), $oRequest['iID']);
				DB::update($sSQL, $aP);
			}
			if ($this->oError['iError'] != 2){
				$sSQL= $this->oError['sSQLInsert'];
				DB::delete('DELETE FROM '. $oRules['oOptions']['sDBTable']);
				foreach ($this->oError['aInsert'] as $aVals){
					DB::insert($sSQL, $aVals);
				}
			}
		}
	}
	
	//==========================
	public function readdatafieldnames(){
		$aFieldsSS = array();
		$iI = 0;
		$iCountFields= 0;
		$iFieldsRow = $this->oOptions['iFieldsOnRowNo'];
		$iI = 0;
		foreach ($this->aSheet->getRowIterator() as $oRow) {
			if (($iI + 1) == $iFieldsRow){
				$aCells = $oRow->getCellIterator();
				$iJ = 0;
				foreach ($aCells as $oCell) {
					$sValue = $oCell->getValue();
					$aFieldsSS[$iJ] = array($sValue, '', -1);
					$iK = 0;
					foreach ($this->aFields as $oField){
						if ((strtolower($oField['sColName']) == strtolower($sValue))
								|| (str_replace('_', '',  strtolower($oField['sDBField']))
										== str_replace(' ', '',  strtolower($sValue)))){
											$aFieldsSS[$iJ][1] = $oField['sDBField'];
											$aFieldsSS[$iJ][2] = $iK;
											$iCountFields++;
											break;
						}
						$iK++;
					}
					$iJ++;
				}
				break;
			} else {
				$iI++;
			}
		}
		return array(
				'aFieldsSS' => $aFieldsSS,
				'iCountFields' => $iCountFields,
				'iFieldsRow' => $iFieldsRow,
		);
	}
	
	//==========================
	public function readdata(){
		$oFieldNames = $this->readdatafieldnames();
		$aFieldsSS = $oFieldNames['aFieldsSS'];
		// loop through data, start after fieldnames row
		$iError = 0;
		$iRowNo = 0;
		$aData = array();
		foreach ($this->aSheet->getRowIterator() as $oRow) {
			if ($iRowNo < $oFieldNames['iFieldsRow']){
				$iRowNo++;
				continue;
			}
			$aCells = $oRow->getCellIterator();
			$aCells->setIterateOnlyExistingCells(FALSE);
			$iColNo = 0;
			$oRow = array();
			$oExtra = array();
			
			foreach ($aCells as $oCell) {
				$sValue = $oCell->getValue();
				if ($aFieldsSS[$iColNo][2] != -1){
					$aFieldsSS[$iColNo][3] = $sValue;
				} else {
					$sFieldName = str_replace
					(' ', '_', strtolower($aFieldsSS[$iColNo][0]));
					$oExtra[$sFieldName] = $sValue;
				}
				$iColNo++;
			}
			if ($iError == 2){
				break;
			}
			
			$aVals = array();
			for ($iK = 0; $iK < $oFieldNames['iCountFields']; $iK++){
				$aVals[] = $aFieldsSS[$iK][3];
			}
			$aVals[] = json_encode($oExtra);
			$aVals[] = date('Y-m-d H:i:s', strtotime('now'));
			$aData[] = $aVals;
			$iRowNo++;
		}
		$sSQL1 = 'INSERT INTO ' . $this->oOptions['sDBTable'] . ' (';
		$sSQL2 = '?';
		foreach ($aFieldsSS as $aField){
			if ($aField[2] != -1){
				$sSQL1 .= $aField[1] . ', ';
				$sSQL2 .= ', ?';
			}
		}
		$sSQL1 .= 'extra, created_at) VALUES (' . $sSQL2 . ', ?)';
		return array(
				'iError' => $iError,
				'aErrors' => 0,
				'sSQLInsert' => $sSQL1,
				'aInsert' => $aData,
		);
	}
	
	//==========================
	public function validate(){
		$aErrors = array();
		$iError = 0;
		if (!$iError) {
			$sFilename = $this->oRequest['sPath'] . $this->oRequest['sTime'] .
			'-' . $this->oRequest['oFile']['name'];
			$sExt = strtolower(File::extension($sFilename));
			if (($sExt != 'xlsx') && ($sExt != 'xls') && ($sExt != 'csv')) {
				$aErrors[] = 'Not a spreadsheet file';
				$iError = 1;
			}
		}
		if (!$iError) {
			if (!file_exists($sFilename)) {
				$aErrors[] = 'Uploaded file does not exist';
				$iError = 1;
			}
		}
		if (!$iError) {
			$sExt1 =(\PhpOffice\PhpSpreadsheet\IOFactory::identify($sFilename));
			$sExt = strtolower($sExt);
			if (($sExt != 'xlsx') && ($sExt != 'xls') && ($sExt != 'csv')) {
				$aErrors[] = 'Not a spreadsheet file';
				$iError = 1;
			}
		}
		if (!$iError){
			try {
				$oReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader
				($sExt1);
				$aSheets = $oReader->listWorksheetNames($sFilename);
				$oReader->setReadDataOnly(true);
				$oReader->setLoadSheetsOnly($aSheets[0]);
				$aSheet = $oReader->load($sFilename);
				$this->aSheet = $aSheet->getActiveSheet();
			} catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $oError) {
				$aErrors[] = 'Error loading file: '.$oError->getMessage();
				$iError = 1;
			}
		}
		return array(
				'iError' => $iError,
				'aErrors' => $aErrors,
		);
	}
}




