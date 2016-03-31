<?php

	$pathForRoot = "./";

	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";

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

	//if( $authobj->getAuth() ){
		$vars = GetFormVars();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<style type="text/css">
.btn {
	background: -moz-linear-gradient(top,#FFF 0%,#E6E6E6);
	background: -webkit-gradient(linear, left top, left bottom, from(#FFF), to(#E6E6E6));
	border: 2px solid #DDD;
	color: #111;
	border-radius: 4px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	-moz-box-shadow: 1px 1px 1px rgba(000,000,000,0.3);
	-webkit-box-shadow: 1px 1px 1px rgba(000,000,000,0.3);
	width: 120px;
	height: 80px;
	margin: 10px 10px;
	font-size:40px;
}
</style>
<script type="text/javascript">
var mytimer = setInterval("timeProc()",2000);
var tester = '';

function timeProc(){
	$('#check_area').load('http://gtl.jp/asp/tabecomu/proc_check.php');
	var hoge = "";
	tester = $('#check_area').html();
	hoge = tester;
	if( tester != "" ){
		clearInterval(mytimer);
		$('#code_area').css('overflow','hidden');
		$('#code_area').height(0);
		$('#r2_area').height(heightBuffer);
		//alert( 'http://gtl.jp/asp/tabecomu/done_confirm.php?PROC_ID='+hoge );
		$('#r2_area').load('http://gtl.jp/asp/tabecomu/done_confirm.php?PROC_ID='+hoge);
	}
}

function DoneProc(moode, proc_id){
	$('#r2_area').height(heightBuffer);
	//alert( 'http://gtl.jp/asp/tabecomu/done_proc.php?DONE_MODE='+moode+'&PROC_ID='+proc_id );
	$('#r2_area').load('http://gtl.jp/asp/tabecomu/done_proc.php?DONE_MODE='+moode+'&PROC_ID='+proc_id);
}


var heightBuffer = 0;
var total = 0;
var totalStr = new String('');
var totalDispStr = new String('');

function BackProc(){
	clearInterval(mytimer);
	$('#code_area').css('overflow','hidden');
	$('#code_area').height(0);
	$('#r2_area').css('overflow','hidden');
	$('#r2_area').height(0);
	$('#calc_area').height(heightBuffer+40);
}

function TenkeyProc(c){
	if( c == 'OK' ){
		heightBuffer =  $('#calc_area').height();
		$('#calc_area').css('overflow','hidden');
		$('#calc_area').height(0);
		$('#code_area').height(heightBuffer);
		$('#disp_code_area').load('http://gtl.jp/asp/tabecomu/disp_barcode.php?SHOP_ID=1&TOTAL='+totalStr);
		mytimer = setInterval("timeProc()",500);
	}else{
		if( c == 'AC' ){
			total = 0;
		}else{
			totalStr = totalStr + c;
			total = Number(totalStr);
		}
		totalStr = total.toString();
		totalDispStr = total.toLocaleString();
		$('#disp_area').html(totalDispStr);
	}
}
</script>
<div id="code_area" style="text-align:center;">
	<div id="disp_code_area">
	</div>
	<button class="btn" style="width:410px;" onclick="javascript:BackProc();">計算に戻る</button>
</div>
<div id="calc_area">
	<div style="width:100%;text-align:center;">
	<div id="disp_area" style="width:370px;height:60px;text-align:right;font-size:60px; border:1px solid #444444;padding: 20px; margin:20px auto 20px auto;"></div>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" style="width:410px;" onclick="javascript:TenkeyProc('AC');">AC</button>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" onclick="javascript:TenkeyProc('1');">1</button>
	<button class="btn" onclick="javascript:TenkeyProc('2');">2</button>
	<button class="btn" onclick="javascript:TenkeyProc('3');">3</button>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" onclick="javascript:TenkeyProc('4');">4</button>
	<button class="btn" onclick="javascript:TenkeyProc('5');">5</button>
	<button class="btn" onclick="javascript:TenkeyProc('6');">6</button>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" onclick="javascript:TenkeyProc('7');">7</button>
	<button class="btn" onclick="javascript:TenkeyProc('8');">8</button>
	<button class="btn" onclick="javascript:TenkeyProc('9');">9</button>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" style="width:270px;" onclick="javascript:TenkeyProc('0');">0</button>
	<button class="btn" onclick="javascript:TenkeyProc('OK');">OK</button>
	</div>
	<div style="width:100%;text-align:center;">
	<button class="btn" style="width:270px;" onclick="javascript:showHistory();">履歴</button>
	</div>
</div>
<div id="check_area" style="overflow:hidden; height:0px; width:0px;"></div>
<div id="r2_area" style="overflow:hidden; height:0px; width:100%;"></div>
<script>
	$('#code_area').css('overflow','hidden');
	$('#code_area').height(0);
	$('#r2_area').css('overflow','hidden');
	$('#r2_area').height(0);
	clearInterval(mytimer);
</script>
<?php
	//}
?>
