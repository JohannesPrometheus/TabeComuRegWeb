<?php

	$pathForRoot = "../";

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
			$user_data = $db->getRow( "select * from user where user_code='".$vars["USER_ID"]."'", DB_FETCHMODE_ASSOC );
			$company_data = $db->getRow( "select * from company where company_code='".$user_data["company_code"]."'", DB_FETCHMODE_ASSOC );
			$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["SHOP_ID"]."'", DB_FETCHMODE_ASSOC );

			$order_data = array( 
				"order_day"		=> date("Y-m-d H:i:s"),
				"regist_id"		=> 0,
				"order_company_pay"	=> $vars["CAT"],
				"group_code"		=> $shop_data["group_code"],
				"group_name"		=> $shop_data["group_name"],
				"shop_code"		=> $shop_data["shop_code"],
				"shop_name"		=> $shop_data["shop_name"],
				"company_code"		=> $company_data["company_code"],
				"company_name"		=> $company_data["company_name"],
				"user_code"		=> $user_data["user_code"],
				"user_name"		=> $user_data["user_lastname"]."　".$user_data["user_firstname"],
				"user_barcode"		=> $user_data["user_barcode"]."",
				"user_paytype"		=> $user_data["user_paytype"],
				"order_lunch_number"	=> 1,
			);

			if( $user_data["user_paytype"] == 1 ){
				$order_data["order_pay"] = ( $vars["TOTAL"] - $vars["CAT"] );
			}else if( $user_data["user_paytype"] == 2 ){
				$order_data["order_salary_pay"] = ( $vars["TOTAL"] - $vars["CAT"] );
			}

			reset( $order_data );
			$query = "insert into `order` set ";
			while( list( $key, $val ) = each( $order_data ) ){
				if( is_numeric( $val ) && $key != "user_barcode" ){
					$query .= $key."=".$val.",";
				}else{
					$query .= $key."='".$val."',";
				}
			}

			$query = substr( $query, 0, strlen( $query ) - 1 );
			
			$result = $db->query( $query );

			if (PEAR::isError($result)) {
			    print $result->getMessage();
				var_dump( $result );
			}

			$query = "select count( order_id ) from `order` where user_code='".$user_data["user_code"]."' and order_day like '".date( "Y-m-" )."%'";
			$num_order = $db->getOne( $query );

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
食べコミュ利用(今月) <?php print $num_order ?>/<?php print $company_data["company_limited_monthly"]; ?><br />
<br />
<button class="btn" onclick="javascript:BackProc3();">終了する</botton>
</div>
<?php
		$db->disconnect();
	//}
?>
