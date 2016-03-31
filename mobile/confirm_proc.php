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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function goProc2(cat){
	$('#cat_area').css( 'overflow', 'hidden' );
	$('#cat_area').height(0);
	$('#result_area').load('<?php print URL_ROOT; ?>mobile/result_proc.php?SHOP_ID=<?php print $vars["SHOP_ID"]; ?>&USER_ID=<?php print $vars["USER_ID"]; ?>&CAT=<?php print $vars["CAT"]; ?>&TOTAL=<?php print $vars["TOTAL"]; ?>');
}
</script>
<div style="width:100%;text-align:center;font-size:20px;">
<div id="cat_area" style="width:100%;text-align:center;font-size:30px;">

<table border="0" align="center" style="font-size:30px;">
<tr><td align="right">お会計　</td><td align="right">￥<?php print number_format( $vars["TOTAL"]*1 ); ?></td></tr>
<tr><td align="right">割引　</td><td align="right">￥<?php print number_format( $vars["CAT"]*1 ); ?></td></tr>
<tr><td align="right">お支払　</td><td align="right">￥<?php print number_format( $vars["TOTAL"]*1 - $vars["CAT"]*1 ); ?></td></tr>
</table>
<br />
金額をご確認ください。<br />
<br />
<button class="btn" onclick="javacript:goProc2();">OK</button><br />
<button class="btn" onclick="javacript:backProc();">訂正</button><br />
</div>
<div id="result_area"></div>
</div>
<?php
		$db->disconnect();
	//}
?>
