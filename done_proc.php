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

			if( $vars["DONE_MODE"] == 1 ){
				$query = "update proc_data set PROC_FLAG=0 where RECORD_ID=".$vars["PROC_ID"];
			}else{
				$query = "delete from proc_data where RECORD_ID=".$vars["PROC_ID"];
			}
			//print "DONE_MODE: ".$vars["DONE_MODE"]."<br />";
			//print "QUERY: ".$query."<br >";
			$db->query( $query );

			$db->disconnect();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;font-size:20px;">
<br />
<?php if( $vars["DONE_MODE"] == 1 ){ ?>
実行しました。<br />
<?php }else{ ?>
中止しました。<br />
<?php } ?>
<br />
<button class="btn" onclick="javacript:BackProc();" style="width:360px;">戻る</button><br />
<br />
</div>
<script>
	$('#check_area').html('');
	tester = "";
</script>
