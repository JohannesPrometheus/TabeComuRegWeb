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

			print "PROC_ID:".$vars["PROC_ID"]."<br />";
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;">
ただいまの決済:￥<?php print number_format( $proc_data["PRICE"]*1 ); ?>(￥<?php print number_format( $proc_data["TOTAL_PRICE"]*1 ); ?>中)<br />
<br />
これでよろしいですか？<br />
<br />
<button class="btn" onclick="javacript:DoneProc(1, <?php print $vars["PROC_ID"]; ?>);" style="width:360px;">決定</button><br />
<button class="btn" onclick="javacript:DoneProc(2, <?php print $vars["PROC_ID"]; ?>);" style="width:360px;">中止</button><br />
<br />
</div>
<?php
		$db->disconnect();
	//}
?>
