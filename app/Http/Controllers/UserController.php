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
public function passwordsave(Request $request)
{
	$this->validate($request, [
			'old' => 'required',
			'password' => 'required|min:6|confirmed',
	]);
	$oUser = \Auth::user();
	$sHashedPassword = $oUser->password;
	if (Hash::check($request->old, $sHashedPassword)) {
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



}