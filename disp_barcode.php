<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

	$perPage = 40;

	$authobj = new Auth("DB", $dsnMember, "memberLogin");

	$authobj->setSessionname (CMS_MEMBER_SESSION);
	//$authobj->start();

	if( array_key_exists( "logoutProc", $_GET ) ){
		if( $_GET["logoutProc"] == "yes" ){
			$authobj->logout();
			$authobj->start();
		}
	}
	// テストだけどね。
	//if( $authobj->getAuth() ){
		$vars = GetFormVars();
?>
<div style="width:100%;text-align:center;">
<img src="http://gtl.jp/asp/tabecomu/qr_img/php/qr_img.php?s=10&d=<?php print $vars["TOTAL"]; ?>">
</div>
<?php
	//}
?>
