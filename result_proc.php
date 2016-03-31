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

			$user_data = $db->getRow( "select * from user_data where RECORD_ID=".$vars["USER_ID"], DB_FETCHMODE_ASSOC );

			$query = "select * from proc_data where USER_ID=".$vars["USER_ID"]." and ENTRY_TIME like '".date("Y-m-d")."%'";
			$today_data = $db->getAll( $query, DB_FETCHMODE_ASSOC );
			$today_total = 0;
			foreach( $today_data as $tmp ){
				$today_total += $tmp["PRICE"];
			}

			$query = "insert into proc_data values (0, ".$vars["USER_ID"].",'".date( "Y-m-d H:i:s" )."', 0,".$vars["CAT"].",".$vars["TOTAL"].",1)";
			$db->query( $query ); 
			$today_total += $vars["CAT"] * 1;
			$user_data["BALANCE"] = ( $user_data["BALANCE"] * 1 + $vars["CAT"] * 1 );
			$query = "update user_data set BALANCE = ".$user_data["BALANCE"]." where RECORD_ID=".$vars["USER_ID"];
			$db->query( $query ); 

			$db->disconnect();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;">
<br />
ありがとうございました。<br />
<br />
<?php print date("Y/m/d H:i"); ?><br />
<br />
<table border="0" align="center" style="font-size:30px;">
<tr><td align="right">お会計　</td><td align="right">￥<?php print number_format( $vars["TOTAL"]*1 ); ?></td></tr>
<tr><td align="right">割引　</td><td align="right">￥<?php print number_format( $vars["CAT"]*1 ); ?></td></tr>
<tr><td align="right">お支払　</td><td align="right">￥<?php print number_format( $vars["TOTAL"]*1 - $vars["CAT"]*1 ); ?></td></tr>
</table>
--------------------------------------<br />
食べコミュ残高 ￥<?php print number_format( $user_data["P_LIMIT"]*1 - $user_data["BALANCE"]*1 ); ?><br />
<br />
<button class="btn" onclick="javascript:BackProc3();">終了する</botton>
</div>
<?php
		$db->disconnect();
	//}
?>
