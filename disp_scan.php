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

			$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["shop_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();


?>
    <div class="logo_box_small">
	<img class="logo_small" src="images/logo.jpg" alt="食べコミュ">
    </div>
    
    <div class="btn_box">
      <input type="number" class="input_common input_user_code middle_text" placeholder="ID" id="user_code_scaned" name="user_code_scaned" onchange="javascript:InputUserCode()";>
      <input type="text" id="user_code_scaned_hide" name="user_code_scaned_hide" onchange="javascript:InputUserCode();" style="width:0px; height:0px;" readonly="readonly">
    </div>

    <div class="btn_box">
	<button class="btn btn_large btn_large_blue">コード入力</button>
    </div>
    
    <div class="message_box small_text">
      <p>
	ご利用金額　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<form id="form2" name="form2" action="<?php print URL_ROOT; ?>result2_proc.php" method="post" target="backwindow">
		<input type="hidden" id="shop_code" name="shop_code" value="<?php print $vars["shop_code"]; ?>">
		<input type="hidden" id="total" name="total" value="<?php print $vars["TOTAL"]; ?>">
		<input type="hidden" id="cat" name="cat">
		<input type="hidden" id="user_code" name="user_code">
		<div id="value_area" name="value_area"></div>
	</form>
      </p>
    </div>
    <iframe name="backwindow" id="backwindow" style="border:none;width:1px;height:1px;"></iframe>

<script>

var resultTimer;
var focusTimer;
var user_code_get;

function InputUserCode(){

	$('#result_area').hide();
	$('#result_area').height(0);

	$('#calc_area').hide();
	$('#calc_area').css('overflow', 'hidden');
	$('#calc_area').height(0);

	NetworkCheck();

	var user_code_tmp = document.getElementById('user_code_scaned').value;
	
	if( user_code_tmp == "" ){
		document.getElementById('user_code_scaned').value = document.getElementById('user_code_scaned_hide').value;
	}
	user_code_get = document.getElementById('user_code_scaned').value;

	var doc = document.getElementsByTagName("iframe")[0].contentWindow.document;
	doc.body.innerHTML="";

	var frm = document.getElementById('form2');
	document.getElementById('cat').value = 0;
	document.getElementById('user_code').value = user_code_get;
	frm.submit();

	$('#calc_area').hide();
	$('#calc_area').css('overflow', 'hidden');
	$('#calc_area').height(0);

	clearTimeout(resultTimer);
	resultTimer = setTimeout( "ResultWin()", 1000 );

	$('#calc_area').hide();
	$('#calc_area').css('overflow', 'hidden');
	$('#calc_area').height(0);
}

function ResultWin(){

	$('#calc_area').hide();
	$('#calc_area').css('overflow', 'hidden');
	$('#calc_area').height(0);

	NetworkCheck();

	clearTimeout(resultTimer);

	var doc = document.getElementsByTagName("iframe")[0].contentWindow.document;
	var tmp_oid = doc.body.innerHTML;


	if(tmp_oid==""){
		onsAlert("ネットワークに接続されていません。");
		showMenu();
	}else{
		if( tmp_oid < 0 ){
			// エラー終了
			if( tmp_oid == "-1" ){
				onsAlert("入力されたコードが正しくありません\nコードをご確認ください。","エラー");
			}else if( tmp_oid == "-2" ){
				onsAlert("既に今月の食べコミュ利用回数の\n上限に達しています。","エラー");
			}else if( tmp_oid == "-3" ){
				onsAlert("既に本日の食べコミュ利用回数の\n上限に達しています。","エラー");
			}else if( tmp_oid == "-4" ){
				onsAlert("店舗IDが正しく取得できませんでした。","エラー");
				showMenu();
			}

			$("#camera_button").show();

			document.getElementById('user_code_scaned').text='';
			document.getElementById('user_code_scaned').value='';
			document.getElementById('user_code_scaned_hide').readOnly = true;
			document.getElementById('user_code_scaned_hide').focus();
			document.getElementById('user_code_scaned_hide').text='';
			document.getElementById('user_code_scaned_hide').value='';
			document.getElementById('user_code_scaned_hide').readOnly = false;

		}else{
			focusfixFlag = false;

			$('#calc_area').hide();
			$('#calc_area').css('overflow', 'hidden');
			$('#calc_area').height(0);

			$('#scan_area').hide(0);
			$('#scan_area').height(0);

			$('#result_area').load('<?php print URL_ROOT; ?>done_confirm.php?order_id=' + tmp_oid, sizeFitting );
			$('#result_area').height(heightBuffer);
		        $("#result_area").delay(1000);
		        $("#result_area").fadeIn(500);
		}
	}

}
function InitResultWin(){
	var doc = document.getElementsByTagName("iframe")[0].contentWindow.document;
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

function FocusCheck(){
	var tmpfid  = $(':focus').attr('id');

	if(focusfixFlag){
		if( tmpfid != 'user_code_scaned' && tmpfid != 'user_code_scaned_hide' ){
			$('#user_code_scaned_hide').attr('readonly',true);
			$('#user_code_scaned_hide').focus();
			$('#user_code_scaned_hide').attr('readonly',false);
		}
	}
	clearTimeout(focusTimer);
}

$('#user_code_scaned').css("width", "65%");

$('#user_code_scaned').blur(function(){
	focusTimer = setTimeout(FocusCheck,500);
});

$('#user_code_scaned_hide').blur(function(){
	focusTimer = setTimeout(FocusCheck,500);
});

</script>
<?php
	}
?>
