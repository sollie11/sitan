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

class ClientController extends Controller{

	use SharedMethods;

public function __construct(){
	$this->middleware('auth');
}


public function formpageoptionsajax(Request $oRequest){
	if (!isset($oRequest->a)){
		echo 0;
		return;
	}
	$oRec = json_decode(base64_decode($oRequest->a));
	$iUserID = Auth::user()->id;
	$sTime = date('Y-m-d H:i:s', strtotime('now'));

	switch ($oRec->sAction){
		case "formoption":
			$oCheck = DB::select('SELECT * FROM answers WHERE ' .
				'user_id = ? AND question_id = ?', 
				array($iUserID, $oRec->iQuestion));
			if (isset($oCheck[0])){
				DB::update('UPDATE answers SET category_id = ?, option_id = ?, ' .
					'updated_at = ? WHERE id = ?', 
					array($oRec->iCat, $oRec->iOption, $sTime, $oCheck[0]->id));
			} else {
				DB::insert('INSERT INTO answers (user_id, category_id, ' .
					'question_id, option_id, created_at, updated_at) VALUES ' .
					'(?, ?, ?, ?, ?, ?)',
					array($iUserID, $oRec->iCat, $oRec->iQuestion, 
						$oRec->iOption, $sTime, $sTime));
			}
			echo '1';
		break;
	}

}


public function formpage($iPageNo){
/*	$aQC = DB::select('SELECT category_id FROM questionnaire_category WHERE ' .
		'questionnaire_id = ?',
		array(Auth::user()->active_questionnaire_id)); */
	$aCats = DB::select('SELECT id, index_no, information, description ' .
		'FROM questions_categories ' .
		'WHERE id IN (SELECT category_id FROM questionnaire_category ' .
		'WHERE questionnaire_id = ?) ORDER BY index_no',
		array(Auth::user()->active_questionnaire_id));

	$iCatFirst = $aCats[0]->index_no;
	$iCatLast = $aCats[sizeof($aCats) - 1]->index_no;
	
	$iF = 0;
	$iI = 0;
	while (($iI < sizeof($aCats)) && (!$iF)){
		if ($aCats[$iI]->index_no == $iPageNo){
			$iF = $iI + 1;
		} else {
			$iI++;
		}
	}
	if ($iF){
		$iCatID = $aCats[$iF - 1]->id;
	} else {
		$iI = 1;
	}
	$aQ = DB::select('SELECT id, index_no, description, tooltip FROM ' .
		'questions WHERE category_id = ?',
		array($iCatID));
	$aO = DB::select('SELECT id, question_id, description FROM questions_options');
	$aA = DB::select('SELECT * FROM answers WHERE user_id = ? AND category_id = ?', 
		array(Auth::user()->id, $iCatID));
	$oData = array(
		'aQuestions' => $aQ,
		'aAnswers' => $aA,
		'aOptions' => $aO,
		'iPageNo' => $iPageNo,
		'aCats' => $aCats,
		'iCatFirst' => $iCatFirst,
		'iCatLast' => $iCatLast,
		'iCatID' => $iCatID,
	);
	return view('client.form')->with('oData', $oData);
}

public function formresults(){
	$oData = $this->answersgraph(Auth::user()->id);
	return view('client.results')->with('oData', $oData);
}

/*
//==========================
public function formsubmit() {
	$oUser = Auth::user();
	$iUserID = $oUser->id;
	$oData = $this->answersgraph($iUserID);
	return view('client.submitconfirm')->with('oData', $oData);
}
*/

//==========================
public function submitted(){
	if (Auth::check()){
		$iUserID = Auth::user()->id;
	} else {
		return;
	}
	$oUser = Auth::user();
	$iUserID = $oUser->id;
	$sDate = date('Y-m-d H:i:s', strtotime('now'));
	$sSQL = 'SELECT A.id AS answer_id, A.category_id, A.question_id, ' .
			'A.option_id, A.comment, ' .
			'B.description AS category, B.index_no AS category_index_no, ' .
			'C.description AS question, C.index_no AS question_index_no, ' .
			'D.description AS optiona, D.index_no AS option_index_no, ' .
			'D.score ' .
			'FROM answers A ' .
			'INNER JOIN questions_categories B ON A.category_id = B.id ' .
			'INNER JOIN questions C ON A.question_id = C.id ' .
			'INNER JOIN questions_options D ON A.option_id = D.id ' .
			'WHERE A.user_id = ? AND A.option_id != 1 ' .
			'ORDER BY A.category_id, A.question_id';
	$aP = array($iUserID);
	$aA = DB::select($sSQL, $aP);
	$sSQL = 'UPDATE forms SET is_active = ? WHERE user_id = ?';
	$aP = array(0, $iUserID);
	DB::update($sSQL, $aP);
	$sSQL = 'INSERT INTO forms (user_id, answers, created_at, updated_at, ' .
			'is_active) ' .
			'VALUES (?, ?, ?, ?, ?)';
	$aP = array($iUserID, json_encode($aA,1), $sDate, $sDate, 1);
	DB::insert($sSQL, $aP);
	return view('client.submitted');
}

/*
public function formsubmit(){
	$oData = array();
	return view('client.submitted')->with('oData', $oData);
}
*/
}
