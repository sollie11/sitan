<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{
	
	public function __construct(){
		$this->middleware('auth');
	}
	
	public function home(){
		$oUser = Auth::user();
		
		if ($oUser->is_admin){
			
			$aUsers = DB::select('SELECT is_admin, is_client, deleted_at ' .
					'FROM users');
			$iAd = 0;
			$iCl = 0;
			$iDel = 0;
			$iAc = 0;
			foreach ($aUsers as $oU){
				if ($oU->deleted_at){
					$iDel++;
				} else {
					$iAc++;
					if ($oU->is_admin == 1){
						$iAd++;
					}
					if ($oU->is_client == 1){
						$iCl++;
					}
				}
			}
			$oData = array(
					'iTotalUsers' => $iAc,
					'iTotalAdmins' => $iAd,
					'iTotalClients' => $iCl,
					'iTotalDeleted' => $iDel,
			);
			
			
			
			return view('homeadmin')->with('oData', $oData);;
		} else {
			$aQs = DB::select('SELECT * FROM question_questionnaire ' .
					'WHERE questionnaire_id = ?',
					array($oUser->active_questionnaire_id));
			$sIN = '0';
			foreach ($aQs as $oQ){
				$sIN .= ', ' . $oQ->question_id;
			}
//			echo json_encode($oUser).'<br>';
//			echo $sIN;die;
			$aQs = DB::select('SELECT A.id, B.description AS category, ' .
					'B.index_no AS category_index_no, A.index_no, ' .
					'A.description AS question, A.tooltip FROM questions A ' .
					'INNER JOIN questions_categories B ON ' .
					'A.category_id = B.id ' .
					'WHERE A.id IN (' . $sIN . ') AND ' .
					'A.deleted_at IS NULL ORDER BY B.index_no, A.index_no');
			//		echo $aQs;die;
//			echo '<br>';
			
			$iQuestionsCount = sizeof($aQs);
			
			$sCategoryFirst = $aQs[0]->category;
			$sCategoryLast = $aQs[sizeof($aQs) - 1]->category;
			
			$aCs = DB::select('SELECT DISTINCT B.description AS category ' .
					'FROM questions A ' .
					'INNER JOIN questions_categories B ON ' .
					'A.category_id = B.id ' .
					'WHERE A.id IN (' . $sIN . ') AND ' .
					'A.deleted_at IS NULL');
			$iCategoriesCount = sizeof($aCs);
			
			$aQNs = DB::select('SELECT id, description FROM ' .
					'questionnaires WHERE id = ?',
					array($oUser->active_questionnaire_id));
			
			$iQNID = $aQNs[0]->id;
			$aPRs = DB::select('SELECT description FROM ' .
					'programmes WHERE id = ?',
					array($oUser->programme_id));
			$aCats = DB::select('SELECT id, index_no, information, description ' .
					'FROM questions_categories ' .
					'WHERE id IN (SELECT category_id FROM questionnaire_category ' .
					'WHERE questionnaire_id = ?) ORDER BY index_no',
					array(Auth::user()->active_questionnaire_id));
			
			$oData = array(
					'sQuestionnaire' => $aQNs[0]->description,
					'sProgramme' => $aPRs[0]->description,
					'sCategoryFirst' => $sCategoryFirst,
					'sCategoryLast' => $sCategoryLast,
					'iCategoriesCount' => $iCategoriesCount,
					'iQuestionsCount' => $iQuestionsCount, 
					'aCats' => $aCats,
			);
			
			/*
			$sQuestionnaire = 'qqqq';
			$sProgramme = 'ppp';
			$oData = array(
				'sQuestionnaire' => $sQuestionnaire,
				'sProgramme' => $sProgramme,
			);
			*/
			return view('home')->with('oData', $oData);;
		}
	}
	
	
}
