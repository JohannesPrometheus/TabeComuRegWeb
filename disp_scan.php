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

			$item_data = $db->getAll( "select * from item_data where SHOP_ID=1 limit 20", DB_FETCHMODE_ASSOC );

			$db->disconnect();


?>
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
	width: 120px;
	height: 80px;
	margin: 10px 10px;
	font-size:40px;
}
</style>
<div style="width:100%;text-align:center;">

<div id="cat_area" style="text-align:center;font-size:30px;">
	<div style="width:100%; text-align:center; height:40px; margin:20px auto 40px auto; virtical-align:middle;">
		<span style="font-size:16px;">会員ID</span>
		<input type="text" id="user_id" name="user_id" style="width:200px;" onFocus="javascript:alert('hoge');">
		<button class="btn_short" style="font-size:16px;width:160px;height:50px;" onclick="javascript:document.getElementById('user_id').focus();">読み取り</button>
	</div>
	<div style="clear:both;"></div>

	利用額を選択してください。<br />
	お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<?php for( $ct = 100; $ct < 600; $ct += 100 ){ ?>
	<button class="btn_short" style="font-size:20px;" onclick="javascript:goProc(<?php print $ct; ?>);"><?php print $ct; ?></button><br />
	<?php } ?>
</div>

</div>
<?php
	//}
?>
