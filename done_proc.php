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
			$user_data = $db->getRow( "select * from user where user_barcode='".$order_data["user_barcode"]."'", DB_FETCHMODE_ASSOC );

			if( $vars["DONE_MODE"] == 1 ){
				$query = "update `tmp_order` set order_confirm_date='".date("Y-m-d H:i:s")."' where order_id=".$vars["order_id"];
				$db->query( $query );

				$query = "select * from `tmp_order` where order_id=".$vars["order_id"];
				$tmpdata = $db->getRow( $query, DB_FETCHMODE_ASSOC );

				unset($tmpdata["order_id"]);

				$result = $db->autoExecute("`order`", $tmpdata, DB_AUTOQUERY_INSERT);
				
			}else if( $vars["DONE_MODE"] == 2 ){
				$query = "delete from `tmp_order` where order_id=".$vars["order_id"];
				$db->query( $query );
			}

			$db->disconnect();
?>
      <div class="logo_box_small">
		<img class="logo_small" src="images/logo.jpg" alt="食べコミュ">
      </div>
<?php 
	if( $vars["DONE_MODE"] == 1 ){ 
?>
      <div class="message_box small_text">
      ありがとうございました
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
        <tr>
          <th> </th>
          <td> </td>
        </tr>
        <tr class="lined_tr">
          <th>計</th>
          <td>￥<?php print number_format( $order_data["order_pay"] ); ?></td>
        </tr>
      </table>

      <div class="message_box">
      <div class="small_text">お支払い金額</div>
      <div class="strong_text">￥<?php print number_format( $order_data["order_pay"] ); ?></div>
      </div>
    </div>

<?php
	}else{
?>
    <div class="price_box" style="width:100%;">
      <p>中止しました。</p>
    </div>
<?php
	}
?>
    
    <div class="btn_box" style="width:100%;">
      <button class="btn btn_large btn_large_blue" onclick="javacript:TenkeyProc('AC');BackProc();">ＯＫ</button>
    </div>

<script>
	$('#check_area').html('');
	tester = "";
</script>
<?php
	}
?>
