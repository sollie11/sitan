<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use File;
use Redirect;

class QuestionsController extends Controller{

public function __construct(){
	$this->middleware('auth');
}

//==========================
public function newquestionsimport(){
	$oData = array(
//		'aEmailForms' => DB::select('SELECT * FROM newquestions_emailforms'),
	);
	return view('questions.import')->with('oData', $oData);
}

//==========================
public function newquestionsimportsave(Request $request){
	$aNC = DB::select('SELECT * FROM new_questions');
	$sTime = date('Y-m-d H:i:s', strtotime('now'));
	$iTotal = 0;
	$iDeleted = 0;
	$sCat = '';
	$iCatIndex = 1;
	$iQIndex = 1;
	$iOIndex = 1;
DB::delete('DELETE FROM questions where id > 1');
DB::delete('DELETE FROM questions_options where id > 1');
DB::delete('DELETE FROM questions_categories where id > 1');
DB::delete('DELETE FROM questionnaires where id > 1');
DB::delete('DELETE FROM question_questionnaire');
foreach($aNC as $oNC){
		if ($oNC->category){
			$sCat = $oNC->category;
		}
		if ($oNC->questionnaire){
			$sQN = $oNC->questionnaire;
		}
		$sDesc = $oNC->question;
		if ($sDesc){
			$iOIndex = 1;
			$iQN = DB::select('SELECT id FROM questionnaires WHERE ' .
				'description = ?', array($sQN));
			if (!isset($iQN[0])){
				DB::insert('INSERT INTO questionnaires (description, is_active, ' .
					'created_at, updated_at) VALUES (?, ?, ?, ?)',
					array($sQN, 1, $sTime, $sTime));
				$iQN = DB::getPDO()->lastInsertID();
			} else {
				$iQN = $iQN[0]->id;
			}
			
			$iCat = DB::select('SELECT id FROM questions_categories WHERE ' .
				'description = ?', array($sCat));
			if (!isset($iCat[0])){
				DB::insert('INSERT INTO questions_categories (description, index_no, ' .
					'created_at, updated_at) VALUES (?, ?, ?, ?)', 
					array($sCat, $iCatIndex, $sTime, $sTime));
				$iCat = DB::getPDO()->lastInsertID();
				$iCatIndex++;
			} else {
				$iCat = $iCat[0]->id;
			}
			$sSQL = 'INSERT INTO questions (category_id, index_no, description, ' .
				'tooltip, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)';
			$aP = array($iCat, $iQIndex, $sDesc, $oNC->tooltip, $sTime, $sTime);
			DB::insert($sSQL, $aP);
			$iQID = DB::getPDO()->lastInsertID();
			DB::insert('INSERT INTO question_questionnaire ' .
				'(question_id, questionnaire_id, value, created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?)',
				array($iQID, $iQN, 1, $sTime, $sTime));
			$iQIndex++;
		}
		// now insert the options
		$sDesc = $oNC->options;
		if ($sDesc){
			DB::insert('INSERT INTO questions_options (question_id, index_no, ' .
				'score, description, created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?, ?)', 
				array($iQID, $iOIndex, $oNC->score, $sDesc, $sTime, $sTime) );
			$iOIndex++;
		}
	}
	$iNumQuestions = DB::select('SELECT id FROM questions WHERE ' .
		'deleted_at IS NULL', array(1));
	$oData = array(
		'iTotal' => $iTotal,
		'iDeleted' => $iDeleted,
		'sSingle' => 'questions',
		'sCaps' => 'Questions',
		'iNumQuestions' => sizeof($iNumQuestions),
	);
	$request->session()->flash('success', 'The questions have been imported.');
	return view('questions.imported')->with('oData', $oData);
}

public function upload(){
	$oData = array('sAction' => 'questions',
		'sSingle' => 'questions',
		'sCaps' => 'Questions',
	);
	return view('upload/upload')->with('oData', $oData);
}


//==========================
public function uploaded(){
	$oData = array(
		'sSingle' => 'questions',
		'sCaps' => 'Questions',
		'sAction' => 'questions',
		'iNumRecords' => sizeof(DB::select('SELECT id FROM new_questions')),
	);
	return view('upload.done')->with('oData', $oData);
}

//==========================
public function uploadedinsert($sAction){
	$sDate = date('Y-m-d H:i:s', strtotime("now"));
	$aNewQuestions = DB::select('SELECT id, category, question, ' .
			'options, score, ' .
			'tooltip, extra FROM new_questions');
	foreach ($aNewQuestions as $oNC){
error_log(2222233);
error_log(json_encode($oNC));
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
		return Redirect::route('questions-uploaded');
	} else {
		return Redirect::route('upload/error/' . $iUploadID);
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




