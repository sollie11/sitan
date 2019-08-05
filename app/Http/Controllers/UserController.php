<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

//==========================
public function password(){
	return view('user.password');
}

//==========================
public function passwordsave(Request $request){
	echo $request->password_confirmation;
	echo '<br>';
//	echo json_encode($request->request);die;
	$this->validate($request, [
			'oldpassword' => 'required',
			'password' => 'required|min:6|confirmed',
	]);
	$oUser = \Auth::user();
	$sHashedPassword = $oUser->password;
	if (Hash::check($request->oldpassword, $sHashedPassword)) {
		//Change the password
		$sSQL = 'UPDATE users SET password = ?, updated_at = ?, ' .
			'is_first_login = 0 ' .
			'WHERE id = ?';
		$aP = array(Hash::make($request->password),
			date('Y-m-d H:i:s', strtotime('now')), $oUser->id);
		DB::update($sSQL, $aP);
		$request->session()->flash('success', 'Your password has been changed.');
		return back();
	}
	$request->session()->flash('failure', 'Your password has not been changed.');
	return back();
}


//==========================
public function personaldetails(){
	return view('user.personaldetails');
}

//==========================
public function personaldetailssave(Request $request){
	$this->validate($request, [
		'first_name' => 'required',
		'surname' => 'required',
		'business_name' => 'required',
	]);
	$iUserID = \Auth::user()->id;
	$sSQL = 'UPDATE users SET name = ?, surname = ?, business_name = ?, ' .
		'updated_at = ? ' .
		'WHERE id = ?';
	$aP = array($request->first_name, $request->surname,
		$request->business_name,
		date('Y-m-d H:i:s', strtotime('now')), $iUserID);
	DB::update($sSQL, $aP);
	$request->session()->flash('success', 'Your personal details have been updated.');
	return back();
}


}