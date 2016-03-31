<?php

	$pathForRoot = "../";

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
		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$user_data = $db->getRow( "select * from user_data where RECORD_ID=".$vars["USER_ID"], DB_FETCHMODE_ASSOC );

			$db->disconnect();
?>
<script>
function goProc(cat){
	$('#cat_area').css( 'overflow', 'hidden' );
	$('#cat_area').height(0);
	alert( 'http://gtl.jp/asp/tabecomu3/mobile/confirm_proc.php?SHOP_ID=<?php print $vars["SHOP_ID"]; ?>&USER_ID=<?php print $vars["USER_ID"]; ?>&CAT='+cat+'&TOTAL=<?php print $vars["TOTAL"]; ?>' );
	$('#result_area').load('http://gtl.jp/asp/tabecomu3/mobile/confirm_proc.php?SHOP_ID=<?php print $vars["SHOP_ID"]; ?>&USER_ID=<?php print $vars["USER_ID"]; ?>&CAT='+cat+'&TOTAL=<?php print $vars["TOTAL"]; ?>' );
}
</script>
<div style="width:100%;text-align:center;font-size:30px;">
<div id="cat_area" style="text-align:center;font-size:30px;">
利用額を選択してください。<br />
お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
<?php for( $ct = 100; $ct < 600; $ct += 100 ){ ?>
<button class="btn" style="font-size:30px;" onclick="javascript:goProc(<?php print $ct; ?>);"><?php print $ct; ?></button><br />
<?php } ?>
</div>
<div id="result_area"></div>
</div>
<?php
		$db->disconnect();
	//}
?>
