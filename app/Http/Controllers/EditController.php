<?php 
namespace App\Http\Controllers;


use File;
use Redirect;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class EditController extends Controller
{
	
	//==========================
	public function emailforms($iEmailFormID){
		$sSQL = 'SELECT * ' .
				'FROM newclients_emailforms ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iEmailFormID));
		if (isset($aNC[0])){
			$oData = $aNC[0];
			return view('admin.edit.emailformsedit')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function emailformssave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'contents' => 'required',
		]);
		
		$sSQL = 'UPDATE newclients_emailforms SET description = ?, ' .
				'contents = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->contents, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The emailssss form has been edited.');
		
		return back();
	}
	
	//==========================
	public function programmes($iProgrammeID){
		$sSQL = 'SELECT * ' .
				'FROM programmes ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iProgrammeID));
		if (isset($aNC[0])){
			$oData = $aNC[0];
			return view('admin.edit.programmesedit')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function programmessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
		]);
		
		$sSQL = 'UPDATE programmes SET description = ?, ' .
				'is_active = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$iIsActive = $request->is_active;
		if ($iIsActive){
			$iIsActive = 1;
		} else {
			$iIsActive = 0;
		}
		$aP = array($request->description, $iIsActive, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The programme has been edited.');
		
		return back();
	}
	
	//==========================
	public function questioncategories($iCategoryID){
		$sSQL = 'SELECT * ' .
				'FROM questions_categories ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iCategoryID));
		if (isset($aNC[0])){
			$oData = $aNC[0];
			return view('admin.edit.questioncategoriesedit')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function questioncategoriessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'graph_description' => 'required',
				'index_no' =>'integer',
		]);
		
		$sSQL = 'UPDATE questions_categories SET description = ?, graph_description = ?, ' .
				'information = ?, index_no = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->graph_description,
				$request->information, $request->index_no, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The category has been edited.');
		
		return back();
	}
	
	//==========================
	public function questionnaires($iQuestionnaireID){
		$oData = array(
				'oQ' => DB::select('SELECT * FROM questionnaires WHERE id = ?',
						array($iQuestionnaireID))
		);
		return view('admin.edit.questionnairesedit')->with('oData', $oData);
	}
	
	//==========================
	public function questionnairessave(Request $request){
		$this->validate($request, [
				'description' => 'required',
		]);
		$iIsActive = $request->is_active;
		if ($iIsActive){
			$iIsActive = 1;
		} else {
			$iIsActive = 0;
		}
		
		$sSQL = 'UPDATE questionnaires SET description = ?, is_active = ?, ' .
				'updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $iIsActive, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The questionnaire has been edited.');
		
		$oData = array(
				'aQuestionnaires' => DB::select('SELECT * FROM questionnaires'),
		);
		session()->put('oSess', $oData);
		
		return back();
	}
	
	//==========================
	public function questions($iQuestionID){
		$sSQL = 'SELECT * ' .
				'FROM questions ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iQuestionID));
		if (isset($aNC[0])){
			$oData = array('oRec' => $aNC[0],
			'aOptions' => DB::select('SELECT description FROM ' .
				'questions_options WHERE question_id = ?',
				array($iQuestionID)
			), );
			return view('admin.edit.questionsedit')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function questionssave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'index_no' =>'integer',
		]);
		
		$sSQL = 'UPDATE questions SET description = ?, tooltip = ?, ' .
				'index_no = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->tooltip,
			$request->index_no, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The question has been edited.');
		
		return back();
	}
	
	//==========================
	public function questionsoptions($iOptionID){
		$sSQL = 'SELECT * ' .
				'FROM questions_options ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iOptionID));
		if (isset($aNC[0])){
			$oData = array('oRec' => $aNC[0],
			'aCategories' => DB::select('SELECT id, description FROM ' .
				'questions_categories ORDER BY index_no'),
			'aQuestions' => DB::select('SELECT id, description FROM ' .
				'questions ORDER BY description'),
			);	
			return view('admin.edit.questionsoptionsedit')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	//==========================
	public function questionsoptionssave(Request $request){
		$this->validate($request, [
				'description' => 'required',
				'index_no' =>'integer',
				'score' =>'integer',
		]);
		
		$sSQL = 'UPDATE questions_options SET description = ?, score = ?, ' .
				'category_id = ?, index_no = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		$aP = array($request->description, $request->score, $request->category,
				$request->index_no, $sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The option has been edited.');
		
		return back();
	}
	
	
	//==========================
	public function clients($iUserID){
		$sSQL = 'SELECT * ' .
				'FROM users ' .
				'WHERE id = ?';
		$aNC = DB::select($sSQL, array($iUserID));
		if (isset($aNC[0])){
			$oData = array(
				'oClient' => $aNC[0],
				'aProgrammes' => DB::select('SELECT * FROM programmes ' .
					'WHERE is_active = 1 AND deleted_at IS NULL'),
					'aProgrammes' => DB::select('SELECT * FROM programmes ' .
						'WHERE is_active = 1 AND deleted_at IS NULL'),
					'aQuestionnaires' => DB::select('SELECT * FROM questionnaires ' .
						'WHERE is_active = 1 AND deleted_at IS NULL'),
					
				);
			return view('edit.clients')->with('oData', $oData);
		} else {
			return back();
		}
	}
	
	
	//==========================
	public function clientssave(Request $request){
		$this->validate($request, [
				'email' => 'email',
				'name' =>'required',
				'surname' =>'required',
		]);
		
		$sSQL = 'UPDATE users SET email = ?, name = ?, ' .
			'surname = ?, updated_at = ? WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
/*		$iIsAdmin = $request->is_admin;
		if ($iIsAdmin){
			$iIsAdmin = 1;
		} else {
			$iIsAdmin = 0;
		}
	*/	
		$aP = array($request->email, $request->name, $request->surname,
			$sTime, $request->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'The user has been edited.');
		
		return back();
	}
	
	
}



