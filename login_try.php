<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

	$_POST["username"] = $_GET["username"];
	$_POST["password"] = $_GET["password"];


	$authobj = new Auth("DB", $dsnMember, "memberLogin");

	$authobj->setSessionname (CMS_MEMBER_SESSION);

	$authobj->start();

	if( array_key_exists( "logoutProc", $_GET ) ){
		if( $_GET["logoutProc"] == "yes" ){
			$authobj->logout();
			$authobj->start();
		}
	}

	if( $authobj->getAuth() ){
		$option = "";
		$db = DB::connect( $dsnDB, $option );
		$db->query( "SET NAMES UTF8" );
		
		$query = "select * from shop where shop_id=".$authobj->getAuthData('shop_id');
		$shop_data = $db->getRow($query, DB_FETCHMODE_ASSOC );
?>
OK|<?php print $shop_data["shop_code"]; ?>|<?php print $shop_data["shop_password"]; ?>
<?php
	}else{
?>
ERROR
<?php
	}
?>
