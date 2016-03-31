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
      <input type="number" class="input_common form_id" placeholder="ID"  id="user_code_scaned" name="user_code_scaned" onchange="javascript:InputUserCode()";>
      <input type="text" id="user_code_scaned_hide" name="user_code_scaned_hide" onchange="javascript:InputUserCode();" style="width:0px; height:0px;" readonly="readonly">
    </div>
    
    <div class="btn_scan_box">
      <button class="btn btn_scan">コード入力</button>
    </div> 
    
    <div class="price_box">
      <p>
	ご利用金額　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<form id="form2" name="form2" action="http://gtl.jp/asp/tabecomu4/result2_proc_t.php" method="post" target="backwindow">
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

var resultTimer;
var user_code_get;

function InputUserCode(){

	var user_code_tmp = document.getElementById('user_code_scaned').value;
	
	if( user_code_tmp == "" ){
		document.getElementById('user_code_scaned').value = document.getElementById('user_code_scaned_hide').value;
	}
	user_code_get = document.getElementById('user_code_scaned').value;

	var frm = document.getElementById('form2');
	document.getElementById('cat').value = 0;
	document.getElementById('user_code').value = user_code_get;
	frm.submit();

	$('#result_area').hide();
	$('#result_area').height(0);

	resultTimer = setTimeout( "ResultWin()", 1000 );
}

function ResultWin(){

	clearTimeout(resultTimer);

	var doc = document.getElementsByTagName("iframe")[1].contentWindow.document;
	var tmp_oid = doc.body.innerHTML;
	if( tmp_oid < 0 ){
		// エラー終了
		if( tmp_oid == "-1" ){
			alert("入力されたコードが正しくありません\nコードをご確認ください。");
		}else{
			if( tmp_oid == "-2" ){
				alert("既に今月の食べコミュ利用回数の\n上限に達しています。");
			}
		}
		document.getElementById('user_code_scaned').text='';
		document.getElementById('user_code_scaned').value='';
		document.getElementById('user_code_scaned_hide').readOnly = true;
		document.getElementById('user_code_scaned_hide').focus();
		document.getElementById('user_code_scaned_hide').text='';
		document.getElementById('user_code_scaned_hide').value='';
		document.getElementById('user_code_scaned_hide').readOnly = false;
		$('#result_area').show();
	}else{
		$('#scan_area').css('overflow','hidden');
		$('#scan_area').height(0);

		$('#result_area').load('http://gtl.jp/asp/tabecomu4/done_confirm.php?order_id=' + tmp_oid );
		$('#result_area').height(heightBuffer);
	        $("#result_area").delay(1000);
	        $("#result_area").fadeIn(500);
	}

}

function InitResultWin(){
	var doc = document.getElementsByTagName("iframe")[1].contentWindow.document;
	doc.body.innerHTML="";
}

document.getElementById('user_code_scaned').text='';
document.getElementById('user_code_scaned').value='';
document.getElementById('user_code_scaned_hide').readOnly = true;
document.getElementById('user_code_scaned_hide').focus();
document.getElementById('user_code_scaned_hide').text='';
document.getElementById('user_code_scaned_hide').value='';
document.getElementById('user_code_scaned_hide').readOnly = false;

InitResultWin();

</script>
<?php
	//}
?>
