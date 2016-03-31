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
		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$proc_data = $db->getRow( "select * from proc_data where RECORD_ID=".$vars["PROC_ID"], DB_FETCHMODE_ASSOC );
			$user_data = $db->getRow( "select * from user_data where RECORD_ID=".$proc_data["USER_ID"], DB_FETCHMODE_ASSOC );

			$db->disconnect();

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;">
<table border="0" align="center" style="font-size:30px;">
<tr><td align="right">お会計　</td><td align="right">￥<?php print number_format( $proc_data["TOTAL_PRICE"]*1 ); ?></td></tr>
<tr><td align="right">割引　</td><td align="right">￥<?php print number_format( $proc_data["PRICE"]*1 ); ?></td></tr>
<tr><td align="right">お支払　</td><td align="right">￥<?php print number_format( $proc_data["TOTAL_PRICE"]*1 - $proc_data["PRICE"]*1 ); ?></td></tr>
</table>
<br />
お手数ですがもう一度ご確認ください。<br />
<br />
<button class="btn" onclick="javacript:DoneProc(1, <?php print $vars["PROC_ID"]; ?>);" style="width:360px;">割引を使う</button><br />
<button class="btn" onclick="javacript:DoneProc(2, <?php print $vars["PROC_ID"]; ?>);" style="width:360px;">取り消し</button><br />
<br />
</div>
<?php
		$db->disconnect();
	//}
?>
