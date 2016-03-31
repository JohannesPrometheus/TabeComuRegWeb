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

	//if( $authobj->getAuth() ){
		$vars = GetFormVars();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<style type="text/css">
.btn {
	background: -moz-linear-gradient(top,#FFF 0%,#E6E6E6);
	background: -webkit-gradient(linear, left top, left bottom, from(#FFF), to(#E6E6E6));
	border: 2px solid #DDD;
	color: #111;
	border-radius: 4px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	-moz-box-shadow: 1px 1px 1px rgba(000,000,000,0.3);
	-webkit-box-shadow: 1px 1px 1px rgba(000,000,000,0.3);
	width: 400px;
	height:160px;
	margin: 10px 10px;
	font-size:30px;
}
</style>
<script type="text/javascript">
</script>
<div id="calc_area">
	<div style="width:100%;text-align:center;">
	<button class="btn" onclick="javascript:window.open('http://gtl.jp/asp/tabecomu/direct_mode.php');">スマートフォン</button>
	<button class="btn" onclick="javascript:window.open('http://gtl.jp/asp/tabecomu/direct_mode.php');">会員証</button>
	<button class="btn" onclick="javascript:window.open('http://gtl.jp/asp/tabecomu/direct_mode.php');">履歴</button>
	</div>
</div>
<script>
<?php
	//}
?>
