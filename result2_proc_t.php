<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools2/special_conf.php";
	require_once $pathForRoot."tools2/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

	/*
	$authobj = new Auth("DB", $dsnMember, "memberLogin");

	$authobj->setSessionname (CMS_MEMBER_SESSION);
	$authobj->start();

	if( array_key_exists( "logoutProc", $_GET ) ){
		if( $_GET["logoutProc"] == "yes" ){
			$authobj->logout();
			$authobj->start();
		}
	}

	if( $authobj->getAuth() ){
	*/
		$vars = GetFormVars();
		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );
			$user_data = $db->getRow( "select * from user where user_barcode='".substr( $vars["user_code"], 0, 7 )."'", DB_FETCHMODE_ASSOC );

			if( count( $user_data ) != 0 ){

				$company_data = $db->getRow( "select * from company where company_code='".$user_data["company_code"]."'", DB_FETCHMODE_ASSOC );
				$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["shop_code"]."'", DB_FETCHMODE_ASSOC );

				$this_month = $db->getOne( "select count( order_id ) from `order` where user_barcode='".$user_data["user_barcode"]."' and order_day like '".date( "Y-m-" )."%'" );


				if( $this_month < $company_data["company_limited_monthly"] ){

					//$vars["cat"] = $company_data["company_userpay"];
					//if( $vars["cat"] > $vars["total"] ) $vars["cat"] = $vars["total"];

					if( $vars["total"] >= 2 * $company_data["company_userpay"] ){
						$company_pay = $company_data["company_userpay"];
						$user_pay = $vars["total"] - $company_pay;
					}else if( $vars["total"] >= $company_data["company_userpay"] ){
						$user_pay = $company_data["company_userpay"];
						$company_pay = $vars["total"] - $user_pay;
					}else if($vars["total"] < $company_data["company_userpay"]){
						$user_pay = 0;
						$company_pay = $vars["total"];
					}
					/*
					$order_data = array( 
						"order_day"		=> date("Y-m-d H:i:s"),
						"regist_id"		=> 0,
						"order_company_pay"	=> $vars["cat"],
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
					*/
					$order_data = array( 
						"order_day"		=> date("Y-m-d H:i:s"),
						"regist_id"		=> 0,
						"order_company_pay"	=> $company_pay,
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
					/*
					if( $user_data["user_paytype"] == 1 ){
						$order_data["order_pay"] = ( $vars["total"] - $vars["cat"] );
					}else if( $user_data["user_paytype"] == 2 ){
						$order_data["order_salary_pay"] = ( $vars["total"] - $vars["cat"] );
					}
					*/

					if( $user_data["user_paytype"] == 1 ){
						$order_data["order_salary_pay"]		= 0;
						$order_data["order_pay"]		= $user_pay;
					}else if( $user_data["user_paytype"] == 2 ){
						$order_data["order_salary_pay"]		= $user_pay;
						$order_data["order_pay"]		= 0;
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

					$query = "select LAST_INSERT_ID() from `order`";
					$order_id = $db->getOne( $query );

					print $order_id;

				}else{
					// 限度回数越え
					print "-2";
				}

			}else{
				// ユーザー該当なし
				print "-1";
			}

			$db->disconnect();


	//}
?>
