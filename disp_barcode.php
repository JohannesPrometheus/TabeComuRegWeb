<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools2/special_conf.php";
	require_once $pathForRoot."tools2/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

	/*
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
	*/
		$vars = GetFormVars();
?>
<div style="width:100%;text-align:center;">
<img src="http://gtl.jp/asp/tabecomu3/qr_img/php/qr_img.php?s=10&d=<?php print $vars["SHOP_ID"]."_".$vars["TOTAL"]; ?>">
</div>
<?php
	//}
?>
