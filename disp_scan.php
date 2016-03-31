<?php

	$pathForRoot = "./";

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
	// テストだけどね。
	//if( $authobj->getAuth() ){
		$vars = GetFormVars();

		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$shop_data = $db->getRow( "select * from shop where shop_code='".$vars["shop_code"]."'", DB_FETCHMODE_ASSOC );

			$db->disconnect();


?>
<div id="cat_area" style="text-align:center;font-size:30px;">
	<div style="width:100%; text-align:center; height:40px; margin:20px auto 40px auto; virtical-align:middle;">
		<span style="font-size:16px;">ID</span>
		<input type="text" id="user_code_scaned" name="user_code_scaned" style="width:200px;">
		<button class="btn_short" style="font-size:16px;width:160px;height:50px;" onclick="javascript:document.getElementById('user_code_scaned').focus();document.getElementById('user_code_scaned').text='';document.getElementById('user_code_scaned').value='';StartUserTimer();">読み取り</button>
	</div>
	<div style="clear:both;"></div>

	利用額を選択してください<br />
	お会計　￥<?php print number_format( $vars["TOTAL"] ); ?><br />
	<form id="form2" name="form2" action="http://gtl.jp/asp/tabecomu2/result2_proc.php" method="post" target="backwindow">
		<input type="hidden" id="shop_code" name="shop_code" value="<?php print $vars["shop_code"]; ?>">
		<input type="hidden" id="total" name="total" value="<?php print $vars["TOTAL"]; ?>">
		<input type="hidden" id="cat" name="cat">
		<input type="hidden" id="user_code" name="user_code">
		<div id="value_area" name="value_area"></div>
	</form>
	<iframe name="backwindow" id="backwindow" style="border:none;width:1px;height:1px;"></iframe>
</div>
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
		$('#value_area').load('http://gtl.jp/asp/tabecomu2/get_user.php?user_barcode='+user_code_get);
		gofProc(100);
	}
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
	$('#result_area').load('http://gtl.jp/asp/tabecomu2/done_confirm.php?order_id=' + tmp_oid );

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
StartUserTimer();

</script>
<?php
	//}
?>
