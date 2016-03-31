<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";

	require_once "Auth/Auth.php";
	require_once "Pager/Pager.php";
	require_once "DB.php";

	$authobj = new Auth("DB", $dsnMember, "memberLogin");

	/*
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
  <div class="container">
    <div class="btn_home_box">
      <button class="btn btn_home" onClick="document.location='index.html'"><img src="images/ico_home.png" alt="最初のページへ" width="32"></button>  
    </div>
    
    <div class="logo_small_box">
      <img src="images/logo.jpg" alt="食べコミュ" width="120">
    </div>

<?php 
	if( $vars["DONE_MODE"] == 1 ){ 
?>
        
    <div class="price_box" style="width:434px;">
      <p>ありがとうございました</p>
      
      <table class="pay" style="width:434px;">
        <tr>
          <th style="width:334px;">ご利用金額</th>
          <td style="width:100px;padding-right:7px;">￥<?php print number_format( $order_data["order_salary_pay"] + $order_data["order_company_pay"] + $order_data["order_pay"] ); ?></td>
        </tr>
        <tr>
          <th style="width:334px;padding-left:0px;">食べコミュご利用額</th>
          <td style="width:100px;padding-right:7px;">￥<?php print number_format(  $order_data["order_company_pay"] *1 ); ?></td>
        </tr>
<?php if( $order_data["order_salary_pay"] * 1 != 0 ){ ?>
        <tr>
          <th style="width:334px;">後払い額</th>
          <td style="width:100px;padding-right:7px;">￥<?php print number_format( $order_data["order_salary_pay"] ); ?></td>
        </tr>
<?php } ?>
        <tr style="height:1px;">
          <th style="width:334px;height:1px;"> </th>
          <td style="width:100px;height:1px;"> </td>
        </tr>
        <tr style="border-top: 1px solid #111;">
          <th style="width:334px;padding-top: 20px;">計</th>
          <td style="width:100px;padding-right:7px;">￥<?php print number_format( $order_data["order_pay"] ); ?></td>
        </tr>
      </table>

      <div style="width:100%;font-size:30px;text-align:center;line-height:60px;">
      <p>お支払い金額</p>
      <p style="font-size:60px;">￥<?php print number_format( $order_data["order_pay"] ); ?></p>
	</div>
    </div>

<?php
	}else{
?>
    <div class="price_box">
      <p>中止しました。</p>
    </div>
<?php
	}
?>
    
    <div class="btn_scan_box">
      <button class="btn btn_scan" onclick="javacript:TenkeyProc('AC');BackProc();">ＯＫ</button>
    </div>
       
  </div>
<script>
	$('#check_area').html('');
	tester = "";
</script>
<?php
	//}
?>
