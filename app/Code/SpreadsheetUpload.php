<?php

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




