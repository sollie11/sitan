<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use File;
use Redirect;
use App\Traits\SharedMethods;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ClientsController extends Controller{

	use SharedMethods;
	
public function __construct(){
	$this->middleware('auth');
}



//==========================
public function download($iUserID){
	$oData = $this->answersgraph($iUserID, 1);
	$oSS = new Spreadsheet();
	$oSheet = $oSS->getActiveSheet();
	$aTotals = array([$oData['sBusinessName']],
			['Category', 'Score'],
	);
	foreach ($oData['aCategories'] as $oRec){
		$aTotals[] = [$oRec->graph_description, intval($oRec->total)];
	}
	$oSheet->fromArray($aTotals, null, 'A1', true);
	$dataSeriesLabels = [
			new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,
					'Worksheet!$B$3', null, 1),
	];
	$xAxisTickValues = [
			new DataSeriesValues
			(DataSeriesValues::DATASERIES_TYPE_STRING,
					'Worksheet!$A$3:$A$11', null, 5),
	];
	$dataSeriesValues = [
			new DataSeriesValues
			(DataSeriesValues::DATASERIES_TYPE_NUMBER,
					'Worksheet!$B$3:$B$11', null, 5),
			new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,
					'Worksheet!$L$3:$L$12', null, 5),
	];
	$series = new DataSeries(
			DataSeries::TYPE_BARCHART,
			NULL,
			range(0, count($dataSeriesValues) - 1),
			$dataSeriesLabels,
			$xAxisTickValues,
			$dataSeriesValues
			);
	$plotArea = new PlotArea(null, [$series]);
	$title = new Title('Summary of scores');
	$xAxisLabel = new Title('Categories');
	$yAxisLabel = new Title('Score');
	// Create the chart
	$chart = new Chart('Categories', $title, null, $plotArea,
			true,
			0, // displayBlanksAs
			null, // xAxisLabel
			null  // yAxisLabel
			);
	
	$chart->setTopLeftPosition('D2');
	$chart->setBottomRightPosition('G20');
	// Add the chart to the worksheet
	$oSheet->addChart($chart);
	
	
	
	
	//		$oSheet->setCellValue('A1', $oData['sBusinessName']);
	$oSheet->getStyle('A1')->applyFromArray(array(
			'font'=>array('bold'=>true, 'size'=>16),
	));
	$oSheet->getStyle('A2:B2')->applyFromArray(array(
			'font'=>array('bold'=>true, 'underline'=>true),
	));
	$oSheet->getStyle('A22:F22')->applyFromArray(array(
			'font'=>array('bold'=>true, 'underline'=>true),
	));
	$oSheet->getStyle('L12')->applyFromArray(array(
			'font'=>array('size'=>1),
	));
	
	$oSheet->setCellValue('A22', 'Category');
	$oSheet->setCellValue('B22', 'Question');
	$oSheet->setCellValue('E22', 'Answer');
	$oSheet->setCellValue('F22', 'Score');
	$oSheet->setCellValue('L12', '100');
	$iRow = 23;
	foreach($oData['aAnswers'] as $oRec){
		$oSheet->setCellValue('A' . $iRow, $oRec->category);
		$oSheet->setCellValue('B' . $iRow, $oRec->question);
		$oSheet->setCellValue('E' . $iRow, $oRec->optiona);
		$oSheet->setCellValue('F' . $iRow, intval
				($oRec->real_score * 10) / 10);
		$iRow++;
	}
	$iI = 0;
	$aW = array(27, 10, 4, 46, 60, 10);
	foreach (range('A','F') as $oCol) {
		$oSheet->getColumnDimension($oCol)->setWidth($aW[$iI]);
		$iI++;
	}
	$sFilename = 'ClientForm_' .
			str_replace(' ', '_', $oData['sBusinessName']) .
			'_' . date('YmdHis', strtotime('now')). '.xlsx';
			$oWriter = new Xlsx($oSS);
			$oWriter->setIncludeCharts(true);
			$sFN = getcwd() . '/';
			$sFN1 = 'download/' . $sFilename;
			$oWriter->save($sFN . $sFN1);
			$iFileSize = filesize($sFN . $sFN1);
			header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			header("Content-Disposition: attachment; filename='$sFilename'");
			return redirect(url($sFN1));
}


//==========================
public function newclientsimport(){
	$oData = array(
		'aEmailForms' => DB::select('SELECT * FROM emailforms'),
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
}


public function results($iUserID){
	$oData = $this->answersgraph($iUserID, 1);
	return view('clients.results')->with('oData', $oData);
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
	$oData = array(
		'sSingle' => 'clients',
		'sCaps' => 'Clients',
		'iNumRecords' => sizeof(DB::select('SELECT id FROM new_clients')),
		'sAction' => 'clients',		
	);
	return view('upload.done')->with('oData', $oData);
}

//==========================
public function uploadedinsert($sAction){
	$sDate = date('Y-m-d H:i:s', strtotime("now"));
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
		$sDesc = $iProgramme;
		if (filter_var($iProgramme, FILTER_VALIDATE_INT) === false){
			$iProgramme = DB::select('SELECT id FROM programmes ' .
				'WHERE description = ?', array($iProgramme));
			if (isset($iProgramme[0])){
				$iProgramme = $iProgramme[0]->id;
			} else {
				DB::insert('INSERT INTO programmes (description, ' .
					'is_active, created_at, updated_at) VALUES(?, ?, ?, ?)', 
					array($sDesc, 1, $sDate, $sDate));
				$iProgramme = DB::getPDO()->lastInsertID();
			}
		}
		$iQuestionnaire = $oNC->questionnaire;
		$sDesc = $iQuestionnaire;
		if (filter_var($iQuestionnaire, FILTER_VALIDATE_INT) === false){
			$iQuestionnaire = DB::select('SELECT id FROM questionnaires ' .
				'WHERE description = ?', array($iQuestionnaire));
			if (isset($iQuestionnaire[0])){
				$iQuestionnaire = $iQuestionnaire[0]->id;
			} else {
				DB::insert('INSERT INTO questionnaires (description, ' .
					'is_active, created_at, updated_at) VALUES(?, ?, ?, ?)', 
					array($sDesc, 1, $sDate, $sDate));
				$iQuestionnaire = DB::getPDO()->lastInsertID();
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
		return Redirect::route('clients-uploaded');
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




