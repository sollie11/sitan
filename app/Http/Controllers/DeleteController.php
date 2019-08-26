<?php 
namespace App\Http\Controllers;


use File;
use Redirect;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
	
	//==========================
	public function clients(Request $oRequest){
		$sSQL = 'UPDATE users SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $oRequest->id));
		$oRequest->session()->flash('success', 'The client has been deleted.');
		return redirect('clients/clients');
	}
	
	//==========================
	public function emailforms($iEmailFormID){
		$sSQL = 'UPDATE newclients_emailforms SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iEmailFormID));
		return redirect('admin/emailforms');
	}
	
	//==========================
	public function newclients(Request $oRequest){
		$sSQL = 'UPDATE new_clients SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $oRequest->id));
		$oRequest->session()->flash('success', 'The new client has been deleted.');
		return redirect('clients/new');
	}
	
	//==========================
	public function questioncategories($iCategoryID){
		$sSQL = 'UPDATE questions_categories SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iCategoryID));
		return redirect('questions/questioncategories');
	}
	
	//==========================
	public function newquestions(Request $oRequest){
		$sSQL = 'UPDATE new_questions SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $oRequest->id));
		$oRequest->session()->flash('success', 'The new question has been deleted.');
		return redirect('questions/new');
	}
	
	//==========================
	public function questions($iQuestionID){
		$sSQL = 'UPDATE questions SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iQuestionID));
		return redirect('questions/questions');
	}
	
	//==========================
	public function questionsoptions($iOptionID){
		$sSQL = 'UPDATE questions_options SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iOptionID));
		return redirect('questions/questionsoptions');
	}
	
	//==========================
	public function programmes($iProgrammeID){
		$sSQL = 'UPDATE programmes SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iProgrammeID));
		return redirect('admin/programmes');
	}
	
	//==========================
	public function questionnaires(Request $oRequest){
		
		$sSQL = 'UPDATE questionnaires SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $oRequest->id));
		$oRequest->session()->flash('success', 'The questionnaire has been deleted.');
		return redirect('questions/questionnaires');
	}
	
	//==========================
	public function users($iUserID){
		$sSQL = 'UPDATE users SET deleted_at = ? ' .
				'WHERE id = ?';
		$sTime = date('Y-m-d H:i:s', strtotime('now'));
		DB::update($sSQL, array($sTime, $iUserID));
		return redirect('admin/users');
	}
	
	
}