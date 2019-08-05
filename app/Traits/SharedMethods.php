<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;

trait SharedMethods {

//==========================
public function gridpaging($sPage, $aData, $aColumns, $oSort){
	if (isset($_GET['grid'])) {
		error_log(111111);
		$aGet = $this->getGET($_GET['grid']);
		error_log(json_encode($aGet));
		if (isset($aGet['page'])){
			$iPageNo = intval($aGet['page']);
		}
	}
	if ((!isset($iPageNo)) || (!$iPageNo)){
		$iPageNo = 1;
	}
	$iNumRecords = sizeof($aData);
	$iResultsPerPage = env('APP_GRID_RESULTS_PER_PAGE', 20);
	$iPages = $iNumRecords / $iResultsPerPage;
	if ($iPages != intval($iPages)) {
		$iPages = intval($iPages + 1);
	}
	$oData = array(
			'aData' => $aData,
			'oPaging' => array(
					'iPageNo' => $iPageNo,
					'iPages' => $iPages,
					'iResultsPerPage' => $iResultsPerPage,
					'iNumRecords' => $iNumRecords,
			),
			'aColumns' => $aColumns,
			'oSort' => $oSort,
	);
	return $oData;
}

private function getGET($sGet){
	$aGet1 = base64_decode($sGet);
	if (!isset($aGet1)){
		return array();
	}
	$aGet1 = explode('&', $aGet1); 
	$aGet = array();
	foreach ($aGet1 as $aRec){
		$aRec = explode('=', $aRec);
		$aGet[$aRec[0]] = $aRec[1];
	}
	return $aGet;
}

	//==========================
	public function gridsetup($aColumns){
		$aDir = array(0, 'ASC', 'DESC');
		$iColumn = 1;
		$iDir = 1;
		if (isset($_GET['grid'])) {
			$aGet = $this->getGET($_GET['grid']);
			error_log(json_encode($aGet));
			if (isset($aGet['sort'])) {
				$iColumn = intval($aGet['sort']);
				$iDir = intval($aGet['dir']);
				if ($iDir == 2) {
					$iDir = 1;
				} else {
					$iDir = 2;
				}
			}
		
		
			if (isset($aGet['sort'])) {
				$iColumn = intval($aGet['sort']);
				$iDir = intval($aGet['dir']);
				if ($iDir == 2) {
					$iDir = 1;
				} else {
					$iDir = 2;
				}
			}
		}
		return array(
				//		'aDir' => $aDir,
				//		'iColumn' => $iColumn,
				'iDir' => $iDir,
				'sSort' => $aColumns[($iColumn - 1)][2],
				'oSort' => array('iColumn' => $iColumn, 'iDirection' => $iDir),
		);
	}
	
	//========================
	public function gridsearchsort($aNC, $iDir, $sSort, $aColumns){
		if ($iDir == 2) {
			array_multisort(array_column($aNC, $sSort),
					SORT_DESC, SORT_NATURAL | SORT_FLAG_CASE, $aNC);
		} else {
			array_multisort(array_column($aNC, $sSort),
					SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE, $aNC);
		}
		if (isset($_GET['grid'])) {
			$aGet = $this->getGET($_GET['grid']);
			if (isset($aGet['search'])){
				$sSearch = $aGet['search'];
				$aNCF = array();
				foreach ($aNC as $oRec) {
					$bFound = 0;
					foreach ($aColumns as $aCol){
						$sField= $aCol[2];
						if (($aCol[3])
								&& (stripos($oRec->$sField, $sSearch) !== false)){
									$bFound = 1;
						}
					}
					if ($bFound) {
						$aNCF[] = $oRec;
					}
					$aNC = $aNCF;
				}
			}
		}
		return $aNC;
	}
	
	
	//==========================
	public function authenticatedlogin($request = 0, $user = 0){
		$oUser = Auth::user();
		$iUserID = $oUser->id;
		$oUser = DB::select('SELECT email, name, surname, username, is_admin, ' .
				'is_first_login, password, business_name FROM users ' .
				'WHERE id = ?', array($iUserID));
		if (!isset($oUser[0])){
			return;
		}
		$oUser = $oUser[0];
		$bIsAdmin = $oUser->is_admin;
		if (!$bIsAdmin){
			$bIsAdmin = -1;
		}
		$oSess = array(
			'sName' => $oUser->name,
			'sSurname' => $oUser->surname,
			'sUsername' => $oUser->username,
			'bIsAdmin' => $bIsAdmin,
			'bIsFirstLogin' => $oUser->is_first_login,
			'sBusinessName' => $oUser->business_name,
		);
		$oNew = DB::select('SELECT id, email, clear_password FROM ' .
			'new_clients WHERE email = ?',array($oUser->email));
		if (isset($oNew[0])){
			$oSess['sPassword'] = $oNew[0]->clear_password;
		}
		session()->put('oUser', $oSess);
	}

	//==========================
	public function answersgraph($iUserID, $bForm = 0) {
		//echo $iUserID;exit;
		$sSQL = 'SELECT business_name ' .
				'FROM users WHERE id = ?';
		$aP = array($iUserID);
		$oData = array('sBusinessName' => 
			DB::select($sSQL, $aP)[0]->business_name);
		if ($bForm){
			$sSQL = 'SELECT id, answers FROM forms WHERE user_id = ? ' .
					'ORDER BY id DESC LIMIT 1';
			$aP = array($iUserID);
			$aA = DB::select($sSQL, $aP);
			$oData['aAnswers'] = json_decode($aA[0]->answers);
		} else {		
			$sSQL = 'SELECT A.id AS answer_id, A.category_id, A.question_id, A.option_id, ' .
				'A.comment, B.score, 0 as real_score ' .
				'FROM answers A ' . 
				'INNER JOIN questions_options B ON A.option_id = B.id ' .
				'WHERE user_id = ? ORDER BY A.category_id';
			$aP = array($iUserID);
			$oData['aAnswers'] = DB::select($sSQL, $aP);
		}
		$sSQL = 'SELECT id, index_no, description, graph_description, ' . 
			'0 AS num_questions, 0 AS total ' .
			'FROM questions_categories WHERE id > ? AND deleted_at IS NULL';
		$aP = array(1);
		$aCategories = DB::select($sSQL, $aP);
		$sSQL = 'SELECT category_id FROM questions ' .
			'WHERE id > 1 AND deleted_at IS NULL';
		$aP = array(1);
		$aQ = DB::select($sSQL, $aP);
		foreach ($aQ as $oRec){
			$iFound = 0;
			$iI = 0;
			while ((!$iFound) && ($iI < sizeof($aCategories))){
				if ($oRec->category_id == $aCategories[$iI]->id){
					$iFound = ($iI + 1);
				} else {
					$iI++;
				}
			}
			if ($iFound){
				$aCategories[$iI]->num_questions++;
			}
		}
		$iJ = 0;
		foreach($oData['aAnswers'] as $oRec){
			$iFound = 0;
			$iI = 0;		
			while ((!$iFound) && ($iI < sizeof($aCategories))){
				if ($oRec->category_id == $aCategories[$iI]->id){
					$iFound = ($iI + 1);
				} else {
					$iI++;
				}
			}
			if ($iFound){
				//$oData['aAnswers'][$iI]->real_score = 
				$iScore = $aCategories[($iFound - 1)]->num_questions;
				$iScore = 100 / $iScore;
				switch ($oRec->score){
					case -5:
						$iScore = $iScore * 4 / 5;
					break;
					case -10:
						$iScore = $iScore * 6 / 10;
					break;
					case -15:
						$iScore = $iScore * 8 / 15;
					break;
				}
				$aCategories[($iFound - 1)]->total += $iScore;
				if ($aCategories[($iFound - 1)]->total > 100){
					$aCategories[($iFound - 1)]->total = 100;
				}
				$oData['aAnswers'][$iJ]->real_score = $iScore;
			}
			$iJ++;
		}
		$oData['aCategories'] = $aCategories;
		return $oData;
	}
}



