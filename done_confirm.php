<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

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

		$vars = GetFormVars();
		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$order_data = $db->getRow( "select * from `tmp_order` where order_id=".$vars["order_id"], DB_FETCHMODE_ASSOC );

			$user_data = $db->getRow( "select * from user where user_code='".$order_data["user_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();

?>
      <div class="logo_box_small">
		<img class="logo_small" src="images/logo.jpg" alt="食べコミュ">
      </div>

      <table class="price_table table_text">
        <tr>
          <th>ご利用金額</th>
          <td>￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td>
        </tr>
        <tr>
          <th>食べコミュご利用額</th>
          <td>￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td>
        </tr>
<?php if( $order_data["order_salary_pay"] * 1 != 0 ){ ?>
        <tr>
          <th>後払い額</th>
          <td>￥<?php print number_format( $order_data["order_salary_pay"] ); ?></td>
        </tr>
<?php } ?>
        <tr class="lined_tr">
          <th>計</th>
          <td>￥<?php print number_format( $order_data["order_pay"] ); ?></td>
        </tr>
      </table>

    <div class="message_box">
	      <div class="small_text">お支払い金額</div>
	      <div class="strong_text">￥<?php print number_format( $order_data["order_pay"] ); ?></div>
	      <div class="small_text">金額をご確認ください</div>
    </div>
    
    <div class="btn_box">
      <button class="btn btn_large btn_large_blue" onClick="javacript:DoneProc(1, <?php print $vars["order_id"]; ?>);">決済する</button>
    </div>
      
    <div class="btn_box">
      <button class="btn btn_large btn_large_red" onclick="javacript:DoneProc(2, <?php print $vars["order_id"]; ?>);">とりけし</button>
    </div>
<?php
		$db->disconnect();
	}
?>
