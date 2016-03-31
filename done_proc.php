<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools2/special_conf.php";
	require_once $pathForRoot."tools2/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

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

			$order_data = $db->getRow( "select * from `order` where order_id=".$vars["order_id"], DB_FETCHMODE_ASSOC );
			$user_data = $db->getRow( "select * from user where user_barcode='".$order_data["user_barcode"]."'", DB_FETCHMODE_ASSOC );

			if( $vars["DONE_MODE"] == 1 ){
				$query = "update `order` set order_confirm_date='".date("Y-m-d H:i:s")."' where order_id=".$vars["order_id"];
			}else{
				$query = "delete from `order` where order_id=".$vars["order_id"];
			}
			//print "DONE_MODE: ".$vars["DONE_MODE"]."<br />";
			//print "QUERY: ".$query."<br >";
			$db->query( $query );

			$db->disconnect();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div style="width:100%;text-align:center;font-size:30px;">
<br />
<?php if( $vars["DONE_MODE"] == 1 ){ ?>
ありがとうございました。<br />
<br />
<style type="text/css">
.table {
	border: 1px solid #aaa;
	border-collapse: collapse;
	border-spacing: 0;
	border-radius: 6px;
}
.table th,
.table td {
	padding: .5em 2em;
	border: 1px solid #aaa;
}
.table thead th {
	background-color: #ddd;
}
.table tbody th {
	background-color: #eee;
}
</style>
<table border="0" align="center" style="font-size:30px;">
<tr><td align="right">お会計　</td><td align="right">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td></tr>
<tr><td align="right">割引　</td><td align="right">￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td></tr>
<tr><td align="right">お支払　</td><td align="right">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_pay"] ); ?></td></tr>
</table>
<?php }else{ ?>
中止しました。<br />
<?php } ?>
<br />
<button class="btn_long" style="margin-top:240px;width:300px;" onclick="javacript:BackProc();">新規お会計</button><br />
<br />
</div>
<script>
	$('#check_area').html('');
	tester = "";
</script>
