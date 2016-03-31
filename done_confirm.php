<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools2/special_conf.php";
	require_once $pathForRoot."tools2/system_tools.php";

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

			$order_data = $db->getRow( "select * from `order` where order_id=".$vars["order_id"], DB_FETCHMODE_ASSOC );

			$user_data = $db->getRow( "select * from user where user_code='".$order_data["user_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();

?>
<div style="width:100%;text-align:center;margin-top:20px;">
<table border="0" align="center" style="font-size:30px;">
<tr><td align="right">お会計　</td><td align="right">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td></tr>
<tr><td align="right">割引　</td><td align="right">￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td></tr>
<tr><td align="right">お支払　</td><td align="right">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_pay"] ); ?></td></tr>
</table>
<br />
お手数ですがもう一度ご確認ください。<br />
<br />
<button class="btn_long" onclick="javacript:DoneProc(1, <?php print $vars["order_id"]; ?>);" style="width:360px;">割引を使う</button><br />
<button class="btn_long" onclick="javacript:DoneProc(2, <?php print $vars["order_id"]; ?>);" style="width:360px;">取り消し</button><br />
<br />
</div>
<?php
		$db->disconnect();
	//}
?>
