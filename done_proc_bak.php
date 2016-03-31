<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools2/special_conf.php";
	require_once $pathForRoot."tools2/system_tools.php";

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
        
    <div class="price_box" style="width:400px;">
      <p>ありがとうございました</p>
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
      <button class="btn btn_scan" onclick="javacript:BackProc();">ＯＫ</button>
    </div>
       
  </div>
<script>
	$('#check_area').html('');
	tester = "";
</script>
<?php
	//}
?>
