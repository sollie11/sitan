<?php 
namespace App\Http\Controllers;


use File;
use Redirect;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Traits\SharedMethods;

class GridController extends Controller
{
	use SharedMethods;
	
	//==========================
	public function clients($bExport = 0){
		$aColumns = array(
			//width, desc, db, search
			array(6, 'ID', 'id', 1),
			array(14, 'Questionnaire', 'questionnaire', 1),
			array(14, 'Programme', 'programme', 1),
			array(25, 'Business Name', 'business_name', 1),
			array(20, 'Email Address', 'email', 1),
			array(20, 'Submitted Form', 'updated_at', 0)
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT A.id, B.description AS programme, A.business_name, A.name, ' .
			'A.surname, A.email, C.description AS questionnaire, ' .
			'D.updated_at FROM users A ' .
			'INNER JOIN programmes B ON B.id=A.programme_id ' .
			'INNER JOIN questionnaires C ON A.active_questionnaire_id = C.id ' .
			'LEFT JOIN forms D ON A.id = D.user_id ' .
			
			'WHERE  A.is_client = 1 ' .
			'AND A.deleted_at IS NULL';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('clients', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.clients')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function emailforms($bExport = 0){
		$sTable = 'emailforms';
		$aColumns = array(
			//width, desc, db, search
			array(10, 'ID', 'id', 1),
			array(50, 'Description', 'description', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .  ' ' .
			'WHERE deleted_at IS NULL AND id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('emailforms', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.emailforms')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function newclients($bExport = 0){
		$sTable = 'new_clients';
		$aColumns = array(
				array(5, 'ID', 'id', 1),
				array(15, 'Questionnaire', 'questionnaire', 1),
				array(15, 'Programme', 'programme', 1),
				array(15, 'Email Address', 'email', 1),
				array(10, 'First Name', 'first_name', 1),
				array(10, 'Surname', 'surname', 1),
				array(15, 'Created', 'created_at', 1)
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable ;
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);		
		if (!$bExport) {
			$oData = $this->gridpaging('newclients', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.newclients')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function newquestions($bExport = 0){
		$sTable = 'new_questions';
		$aColumns = array(
				array(5, 'ID', 'id', 1),
				array(15, 'Questionnaire', 'questionnaire', 1),
				array(15, 'Category', 'category', 1),
				array(15, 'Question', 'question', 1),
				array(15, 'Options', 'options', 1),
				array(10, 'Score', 'score', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable ;
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('newquestions', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.newquestions')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function programmes($bExport = 0){
		$sTable = 'programmes';
		$aColumns = array(
				array(10, 'ID', 'id', 1),
				array(50, 'Programme', 'description', 1),
				array(10, 'Active', 'is_active', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .
			' WHERE deleted_at IS NULL AND id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('programmes', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.programmes')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function questionscategories($bExport = 0){
		$sTable ='questions_categories';
		$aColumns = array(
				array(10, 'ID', 'id', 1),
				array(10, 'Index', 'index_no', 1),
				array(60, 'Description', 'description', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .
		' WHERE deleted_at IS NULL AND id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('questioncategories', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.questionscategories')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function questionsoptions($bExport = 0){
		$sTable = 'questions_options';
		$aColumns = array(
				array(5, 'ID', 'id', 1),
				array(35, 'Question', 'question', 1),
				array(35, 'Option', 'option', 1),
				array(10, 'Score', 'score', 1),
				array(10, 'Index', 'index_no', 1),		
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT A.id, B.description AS question, ' .
				'A.description AS option, A.score, A.index_no ' .
				'FROM questions_options A INNER JOIN questions B ON A.question_id = B.id ' .
				'WHERE A.id > 1 AND A.deleted_at IS NULL';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('questionsoptions', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.questionsoptions')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	//==========================
	public function questionnairesassign($iQuestionnaireID){
		$aColumns = array(
				array(7, 'ID', 'id', 1),
				array(22, 'Category', 'category', 1),
				array(40, 'Description', 'description', 1),
		);
		$sSQL = 'SELECT A.id, B.description AS category, A.description, ' .
				'A.index_no ' .
				'FROM questions A ' .
				'INNER JOIN questions_categories B ON A.category_id = B.id ' .
				'WHERE A.deleted_at IS NULL AND ' .
				'A.id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		$oData = $this->gridpaging('questionnairesassign', $aRecs, $aColumns, $oDir['oSort']);
		$sQu = DB::select('SELECT description FROM questionnaires WHERE ' .
			'id = ?', array($iQuestionnaireID));
		if (isset($sQu[0])){
			$oData['sQu'] = $sQu[0]->description;
			$oData['aQQ'] = DB::select('SELECT * FROM question_questionnaire');
			$oData['iQNID'] = $iQuestionnaireID;
			return view('admin.grid.questionnairesassign')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function questionnaires($bExport = 0){
		$sTable ='questionnaires';
		$aColumns = array(
				array(10, 'ID', 'id', 1),
				array(60, 'Description', 'description', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .
		' WHERE deleted_at IS NULL AND id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);	
		if (!$bExport) {
			$oData = $this->gridpaging('questionnaires', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.questionnaires')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}

	//==========================
	public function questions($bExport = 0){
		$sTable ='questions';
		$aColumns = array(
				array(6, 'ID', 'id', 1),
				array(6, 'Index', 'index_no', 1),
				array(25, 'Category', 'category', 1),
				array(50, 'Description', 'description', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT A.id, B.description AS category, A.index_no, ' .
			'A.description FROM questions A ' .
			'INNER JOIN questions_categories B ON A.category_id = B.id ' .
			' WHERE A.deleted_at IS NULL AND ' .
			'A.id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);		
		if (!$bExport) {
			$oData = $this->gridpaging('questions', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.questions')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
		
	
	//==========================
	public function users($bExport = 0){
		$sTable ='users';
		$aColumns = array(
				array(6, 'ID', 'id', 1),
				array(30, 'EMail', 'email', 1),
				array(25, 'Name', 'name', 1),
				array(25, 'Surname', 'surname', 1),
				array(5, 'Admin', 'is_admin', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .
		' WHERE deleted_at IS NULL AND id > 1';
		$aRecs = DB::select($sSQL);
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('users', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.users')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
		
	//==========================
	public function uploads($bExport = 0){
		$sTable ='uploads';
		$aColumns = array(
				array(5, 'ID', 'id', 1),
				array(70, 'Data', 'data', 1),
				array(10, 'Error', 'error', 1),
				array(15, 'Created at', 'created_at', 1),
		);
		$sSQL = '';
		foreach ($aColumns as $aCol){
			$sSQL .= $aCol[2]. ', ';
		}
		$sSQL = 'SELECT ' . substr($sSQL, 0, -2) . ' FROM ' . $sTable .
		' WHERE id > 1';
		$aRecs = DB::select($sSQL);
		foreach ($aRecs as $oRec){
			$oRec->data = str_replace(',', ', ', $oRec->data);
		}
		$oDir = $this->gridsetup($aColumns);
		$aRecs = $this->gridsearchsort($aRecs, $oDir['iDir'], $oDir['sSort'], $aColumns);
		if (!$bExport) {
			$oData = $this->gridpaging('uploads', $aRecs, $aColumns, $oDir['oSort']);
			return view('grids.uploads')->with('oData', $oData);
		} else {
			return $aRecs;
		}
	}
	
	
	
}