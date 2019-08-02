<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
public function __construct(){
	$this->middleware('auth');
}

public function index(){
	$oUser = Auth::user();
	if ($oUser->is_admin){
		return view('homeadmin');
	}
	if ($oUser->is_client){
		$aQs = DB::select('SELECT * FROM question_questionnaire ' .
			'WHERE questionnaire_id = ?',
			array($oUser->active_questionnaire_id));
		$sIN = '0';
		foreach ($aQs as $oQ){
			$sIN .= ', ' . $oQ->question_id;
		}
		echo $sIN;
		$aQs = DB::select('SELECT A.id, B.description AS category, ' .
			'B.index_no AS category_index_no, A.index_no, ' .
			'A.description AS question, A.tooltip FROM questions A ' .
			'INNER JOIN questions_categories B ON ' .
			'A.category_id = B.id ' .
			'WHERE A.id IN (' . $sIN . ') AND ' .
			'A.deleted_at IS NULL ORDER BY B.index_no, A.index_no');
		echo '<br>';
		foreach ($aQs as $oQ){
			echo json_encode($oQ).'<br>';
		}
		
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
		$oData = array(
			'sQuestionnaire' => $aQNs[0]->description,
			'sProgramme' => $aPRs[0]->description,
			'sCategoryFirst' => $sCategoryFirst,
			'sCategoryLast' => $sCategoryLast,
			'iCategoriesCount' => $iCategoriesCount,
			'iQuestionsCount' => $iQuestionsCount
		);
		return view('homeclient')->with('oData', $oData);
	}
}

}
