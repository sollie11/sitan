<?php 
namespace App\Http\Controllers;


use File;
use Redirect;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AddController extends Controller{

//==========================
public function clients(){
	$oData = array(
		'aProgrammes' => DB::select('SELECT id, description FROM programmes ' .
			'WHERE is_active = 1 AND deleted_at IS NULL'),
		'aQuestionnaires' => DB::select('SELECT id, description FROM questionnaires ' .
			'WHERE is_active = 1 AND deleted_at IS NULL'),
	);
	return view('add.clients')->with('oData', $oData);
}

	
//==========================
public function clientssave(Request $request){
	$this->validate($request, [
			'email' => 'email',
			'business_name' => 'required',
	]);
	$bDupl = DB::select('SELECT id FROM users WHERE email = ?',
			array($request->email));
	if (isset($bDupl[0])){
		$request->session()->flash('failure', 'Existing email, not added.');
	} else {
		$sSQL = 'INSERT INTO users (email, name, surname, business_name, ' .
				'active_questionnaire_id, programme_id, ' .
				'is_client, created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->email, $request->first_name,
			$request->surname, $request->business_name, $request->questionnaire,
			$request->programme, 1, $sTime, $sTime);
		DB::insert($sSQL, $aP);
		$request->session()->flash('success', 'Client added successfully.');
	}
	
	return back();
}


//==========================
public function newclients(){
	$oData = array(
			'aProgrammes' => DB::select('SELECT id, description FROM programmes ' .
					'WHERE is_active = 1 AND deleted_at IS NULL'),
			'aQuestionnaires' => DB::select('SELECT id, description FROM questionnaires ' .
					'WHERE is_active = 1 AND deleted_at IS NULL'),
	);
	return view('add.newclients')->with('oData', $oData);
}


//==========================
public function newclientssave(Request $request){
	$this->validate($request, [
			'email' => 'email',
			'business_name' => 'required',
	]);
	$bDupl = DB::select('SELECT id FROM new_clients WHERE email = ?',
			array($request->email));
	if (isset($bDupl[0])){
		$request->session()->flash('failure', 'Existing email, not added.');
	} else {
		$sPr = DB::select('SELECT description FROM programmes WHERE id = ?',
				array($request->programme));
		if (isset($sPr[0])){
			$sPr = $sPr[0]->description;
		} else {
			$sPr = '';
		}
		$sQn = DB::select('SELECT description FROM questionnaires WHERE id = ?',
				array($request->questionnaire));
		if (isset($sQn[0])){
			$sQn = $sQn[0]->description;
		} else {
			$sQn = '';
		}
		$sSQL = 'INSERT INTO new_clients (email, first_name, surname, business_name, ' .
				'questionnaire, programme, created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->email, $request->first_name,
				$request->surname, $request->business_name, $sQn,
				$sPr, $sTime, $sTime);
		DB::insert($sSQL, $aP);
		$request->session()->flash('success', 'New client added successfully.');
	}
	
	return back();
}


//==========================
	public function programmes(){
		return view('admin.add.programmesadd');
	}
	
	//==========================
	public function programmessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
		]);
		
		$sSQL = 'INSERT INTO programmes (description, is_active, ' .
				'created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$iIsActive = $request->is_active;
		if ($iIsActive){
			$iIsActive = 1;
		} else {
			$iIsActive = 0;
		}
		$aP = array($request->description, $iIsActive, $sTime, $sTime);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The programme has been added.');
		
		return back();
	}
	
	//==========================
	public function questioncategories(){
		return view('add.questioncategories');
	}
	
	//==========================
	public function questioncategoriessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'graph_description' => 'required',
				'index_no' => 'integer',
		]);
		
		$sSQL = 'INSERT INTO questions_categories (description, graph_description, ' .
				'information, index_no, ' .
				'created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->graph_description,
				$request->information, $request->index_no, $sTime, $sTime);
		DB::insert($sSQL, $aP);
		$request->session()->flash('success', 'The category has been added.');
		
		return back();
	}
	
	//==========================
	public function questionnaires(){
		return view('add.questionnaires');
	}
	
	//==========================
	public function questionnairessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
		]);
		
		$sSQL = 'INSERT INTO questionnaires (description, created_at, ' .
				'updated_at, is_active) VALUES (?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $sTime, $sTime, 1);
		DB::insert($sSQL, $aP);
		$request->session()->flash('success', 'The questionnaire has been added.');
	/*	
		$oData = array(
				'aQuestionnaires' => DB::select('SELECT * FROM questionnaires'),
		);
		session()->put('oSess', $oData);
		*/
		return back();
	}
	
	//==========================
	public function questions(){
		return view('admin.add.questionsadd');
	}
	
	//==========================
	public function questionsoptions(){
		$oData = array(
				'aQuestions' => DB::select('SELECT id, description FROM ' .
						'questions ORDER BY description'),
		);
		return view('admin.add.questionsoptionsadd')->with('oData', $oData);
	}
	
	//==========================
	public function questionsoptionssave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'index_no' => 'integer',
				'score' => 'integer',
		]);
		$sSQL = 'INSERT INTO questions_options (description, index_no, score, ' .
				'question_id, ' .
				'created_at, updated_at) ' .
				'VALUES (?, ?, ?, ?, ?, ?)';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->index_no,
				$request->score, $request->question,
				$sTime, $sTime);
		DB::insert($sSQL, $aP);
		$request->session()->flash('success', 'The option has been added.');
		
		return back();
	}
	
	//==========================
	public function users(){
		return view('admin.add.usersadd');//->with('oData', $oData);
	}
	
	//==========================
	public function userssave(Request $request){
		$this->validate($request, [
				'email' => 'email',
				'name' => 'required',
				'surname' => 'required',
		]);
		$bIsAdmin = $request->is_admin;
		if ($bIsAdmin == 'on'){
			$bIsAdmin = 1;
		} else {
			$bIsAdmin = 0;
		}
		$bDupl = DB::select('SELECT id FROM users WHERE email = ?',
			array($request->email));
		if (isset($bDupl[0])){
			die;
			$request->session()->flash('alert-danger', 'Existing email, not added.');
		} else {
			$sSQL = 'INSERT INTO users (email, name, surname, is_admin, ' .
					'created_at, updated_at) ' .
					'VALUES (?, ?, ?, ?, ?, ?)';
			$sTime = date('Y-m-d H:i:s', strtotime('now'));
			$aP = array($request->email, $request->name,
					$request->surname, $bIsAdmin,
					$sTime, $sTime);
			DB::insert($sSQL, $aP);
		}
		
		return back();
	}
	
	
	
	
}