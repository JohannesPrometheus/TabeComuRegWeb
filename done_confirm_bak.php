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

			$order_data = $db->getRow( "select * from `order` where order_id=".$vars["order_id"], DB_FETCHMODE_ASSOC );

			$user_data = $db->getRow( "select * from user where user_code='".$order_data["user_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();

?>
  <div class="container">
    
    <div class="btn_home_box">
      <button class="btn btn_home" onClick="javascript: showMenu();"><img src="images/ico_home.png" alt="最初のページへ" width="32"></button>
    </div>
    
    <div class="logo_small_box">
      <img src="images/logo.jpg" alt="食べコミュ" width="120">
    </div>
        
    <div class="price_box" style="width:400px;">
      <table class="pay" style="width:400px;">
        <tr>
          <th style="width:300px;">ご利用金額</th>
          <td style="width:100px;">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td>
        </tr>
        <tr>
          <th style="width:300px;">食べコミュ利用額</th>
          <td style="width:100px;">￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td>
        </tr>
        <tr>
          <th style="width:300px;">お支払い金額</th>
          <td style="width:100px;">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_pay"] ); ?></td>
        </tr>
      </table>


      <table class="pay" style="width:400px;">
        <tr>
          <th style="width:300px;">ご利用金額</th>
          <td style="width:100px;">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td>
        </tr>
        <tr>
          <th>食べコミュ利用額</th>
          <td>￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td>
        </tr>
        <tr>
          <th style="padding-bottom: 20px;">後払い額</th>
          <td>￥<?php print number_format( $order_data["order_salary_pay"] ); ?></td>
        </tr>
        <tr style="border-top: 1px solid #111;">
          <th style="padding-top: 20px">計</th>
          <td>￥<?php print number_format(  $order_data["order_company_pay"] + $order_data["order_salary_pay"] ); ?></td>
        </tr>
      </table>
      
      <p>お支払い金額</p>
      <p>￥<?php print number_format( $order_data["order_pay"] ); ?></p>


      
      <p style="font-size: 14px;">金額をご確認ください</p>
      
    </div>
    
    <div class="btn_scan_box">
      <button class="btn btn_scan" onClick="javacript:DoneProc(1, <?php print $vars["order_id"]; ?>);">決済する</button>
    </div>
      
    <div class="btn_modi_box">
      <button class="btn btn_modi" onclick="javacript:DoneProc(2, <?php print $vars["order_id"]; ?>);">とりけし</button>
    </div>

  </div>
<?php
		$db->disconnect();
	//}
?>
