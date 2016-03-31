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

			$item_data = array();
			if( array_key_exists( "ITEM_ID", $vars ) ){
				$tmpItemId = str_replace( "item", "", $vars["ITEM_ID"] );
				$item_data = $db->getRow( "select * from item_data where RECORD_ID=".$tmpItemId, DB_FETCHMODE_ASSOC );
				$query = "insert into proc_data values (0, ".$vars["USER_ID"].",'".date( "Y-m-d H:i:s" )."', ".$tmpItemId.",".$item_data["PRICE"].",1)";
				$db->query( $query ); 
				$today_total += $item_data["PRICE"] * 1;
				$user_data["BALANCE"] = ( $user_data["BALANCE"] * 1 - $item_data["PRICE"] * 1 );
				$query = "update user_data set BALANCE = ".$user_data["BALANCE"]." where RECORD_ID=".$vars["USER_ID"];
				$db->query( $query ); 

			}
			$db->disconnect();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;">
今月の決済可能残額:￥<?php print number_format( $user_data["P_LIMIT"]*1 - $user_data["BALANCE"]*1 ); ?><br />
今日の決済合計:￥<?php print number_format( $today_total*1 ); ?><br />
<br />
<?php if( array_key_exists( "PRICE", $item_data ) ){ ?>ただいまの決済:￥<?php print number_format( $item_data["PRICE"]*1 ); ?><?php } ?><br />
</div>
<?php
		$db->disconnect();
	//}
?>
