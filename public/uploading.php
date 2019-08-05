<?php

$sTargetPath = getcwd() . "/upload/";
//echo 'xxx'.$sTargetPath.'xxx';
$sTime = date('YmdHis', strtotime("now"));
$oReturn = array(
	"oFile"=>$_FILES['filetoupload'],
	"sPath"=>$sTargetPath,
	"sTime"=>$sTime,
	"sPage"=>$_POST["page"],
);
if (move_uploaded_file($_FILES['filetoupload']['tmp_name'], 
	$sTargetPath . $sTime . '-' . $_FILES['filetoupload']['name'])) {
	$oReturn["sMessage"] = "Success";
	$oReturn["sColor"] = "#008800";
	$oReturn["bSuccess"] = 1;
} else{
	$oReturn["sMessage"] = "Error";
	$oReturn["sColor"] = "#ff0000";
	$oReturn["bSuccess"] = 0;
}
echo base64_encode(json_encode($oReturn, 1));

