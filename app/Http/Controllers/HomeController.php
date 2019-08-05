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
	}
	return view('home');
}


}
