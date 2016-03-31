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

		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["shop_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();


?>
<style type="text/css">
</style>
<div style="width:100%;text-align:center;">
<div id= "value_area">
</div>
<div id="cat_area" style="text-align:center;font-size:30px;">
	<div style="width:400px; text-align:center; height:40px; margin:20px auto 40px auto;">
		<div style="width:80px; margin:0px auto 0 10px; height:40px; border:0px black solid; float:left;font-size:20px;">ID</div>
		<div style="width:180px; margin:0px auto 0 10px; height:40px; border:1px black solid; float:left;">
			<input type="text" id="user_code_scaned" name="user_code_scaned" onchange="javascript:alert('hoge');">
		</div>
		<div style="width:100px; margin:0px auto 0 10px; height:40px; border:0px black solid; float:left;">
			<button class="btn_short" onclick="javascript:$('#user_code_scaned').focus();StartUserTimer();" style="width:100px;font-size:16px; height:40px;margin:0px; padding:0px;">読み取り</button>
		</div>
	</div>
	<div style="clear:both;"></div>

	利用額を選択してください。<br />
	お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />

</div>
<script>
var user_timer;
var user_code_get='';
var user_code_bak='';

function CheckUserCode(){
	alert('hoge');
	user_code_bak = user_code_get;
	user_code_get = document.getElementById('user_code_scaned').value;
	if( user_code_get != user_code_bak ){
		alert( user_code_get );
		clearInterval( user_timer );
	}
}

function StartUserTimer(){
	user_timer = window.setInterval("CheckUserCode()", 3000);
}
</script>
<?php
	//}
?>
