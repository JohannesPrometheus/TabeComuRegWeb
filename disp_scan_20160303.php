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

			$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["shop_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();


?>
<div id="cat_area" class="container">
    
    <div class="btn_home_box">
      <button class="btn btn_home" onClick="javascript: showMenu();"><img src="images/ico_home.png" alt="最初のページへ" width="32"></button>
    </div>
    
    <div class="logo_small_box">
      <img src="images/logo.jpg" alt="食べコミュ" width="120">
    </div>
    
    <div class="input_id_box">
      <input type="text" class="input_common form_id" placeholder="ID"  id="user_code_scaned" name="user_code_scaned" onchange="javascript:InputUserCode()";>
    </div>
    
    <div class="btn_scan_box">
      <button class="btn btn_scan">手入力</button>
    </div> 
    
    <div class="price_box">
      <p>
	お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<form id="form2" name="form2" action="http://gtl.jp/asp/tabecomu3/result2_proc.php" method="post" target="backwindow">
		<input type="hidden" id="shop_code" name="shop_code" value="<?php print $vars["shop_code"]; ?>">
		<input type="hidden" id="total" name="total" value="<?php print $vars["TOTAL"]; ?>">
		<input type="hidden" id="cat" name="cat">
		<input type="hidden" id="user_code" name="user_code">
		<div id="value_area" name="value_area"></div>
	</form>
      </p>
    </div>
    <iframe name="backwindow" id="backwindow" style="border:none;width:1px;height:1px;"></iframe>
</div>

<script>

var user_timer;
var user_code_get='';
var user_code_bak='';


var resultTimer;


function CheckUserCode(){
	user_code_bak = user_code_get;
	user_code_get = document.getElementById('user_code_scaned').value;
	if( user_code_get != user_code_bak ){
		clearInterval( user_timer );
		$('#value_area').load('http://gtl.jp/asp/tabecomu3/get_user.php?user_barcode='+user_code_get);
		gofProc(100);
	}
}

function InputUserCode(){
	clearInterval( user_timer );
	$('#value_area').load('http://gtl.jp/asp/tabecomu3/get_user.php?user_barcode='+user_code_get);
	gofProc(100);
}

function StartUserTimer(){
	user_code_get='';
	user_code_bak='';
	user_timer = window.setInterval("CheckUserCode()", 500);
}

function ResultWin(){
	var doc = document.getElementsByTagName("iframe")[1].contentWindow.document;
	var tmp_oid = doc.body.innerHTML;

	$('#scan_area').css('overflow','hidden');
	$('#scan_area').height(0);
	$('#result_area').height(heightBuffer);
	$('#result_area').load('http://gtl.jp/asp/tabecomu3/done_confirm.php?order_id=' + tmp_oid );

}

function gofProc(cat){
	clearInterval(mytimer);

	resultTimer = window.setTimeout('ResultWin()', 1000 );

	var frm = document.getElementById('form2');
	document.getElementById('cat').value = cat;
	document.getElementById('user_code').value = user_code_get;
	frm.submit();

}

document.getElementById('user_code_scaned').focus();
document.getElementById('user_code_scaned').text='';
document.getElementById('user_code_scaned').value='';
//StartUserTimer();

</script>
<?php
	//}
?>
