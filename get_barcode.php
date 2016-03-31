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
	<div style="width:400px; text-align:center; height:40px; margin:20px auto 40px auto;">
		<div style="width:80px; margin:0px auto 0 10px; height:40px; border:0px black solid; float:left;font-size:20px;">会員ID</div>
		<div style="width:180px; margin:0px auto 0 10px; height:40px; border:1px black solid; float:left;"></div>
		<div style="width:100px; margin:0px auto 0 10px; height:40px; border:0px black solid; float:left;">
	<button class="btn" style="width:100px;font-size:16px; height:40px;margin:0px; padding:0px;">読み取り</button>
</div>
	</div>
	<div style="clear:both;"></div>

	利用額を選択してください。<br />
	お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<?php for( $ct = 100; $ct < 600; $ct += 100 ){ ?>
	<button class="btn" style="font-size:20px;" onclick="javascript:goProc(<?php print $ct; ?>);"><?php print $ct; ?></button><br />
	<?php } ?>
</div>

</div>
<?php
	//}
?>
