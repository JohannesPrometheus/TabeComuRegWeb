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
		$option = "";
		$db = DB::connect( $dsnDB, $option );
			$db->query( "SET NAMES UTF8" );

			$item_data = $db->getAll( "select * from item_data where SHOP_ID=1 limit 20", DB_FETCHMODE_ASSOC );

			$db->disconnect();
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
flags = new Array();
<?php
	reset( $item_data );
	foreach( $item_data as $tmp ){
?>
flags[<?php print $tmp["RECORD_ID"]; ?>] = false;
<?php
	}
?>
</script>
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
	width: 100px;
	padding: 10px 0;
}
</style>
<div style="width:100%;text-align:center;">
<?php
	reset( $item_data );
	foreach( $item_data as $tmp ){
?>
<button class="btn" style="width:80%;height:60px;margin-bottom:10px;" onclick="javascript:if( flags[<?php print $tmp["RECORD_ID"]; ?>] ){ $('#image<?php print $tmp["RECORD_ID"]; ?>').hide(); flags[<?php print $tmp["RECORD_ID"]; ?>] = false; }else{ $('#image<?php print $tmp["RECORD_ID"]; ?>').show(); flags[<?php print $tmp["RECORD_ID"]; ?>] = true; }" id="item<?php print $tmp["RECORD_ID"]; ?>"><?php print $tmp["ITEM_NAME"]."\t".$tmp["PRICE"]; ?></button><br />
<div id="image<?php print $tmp["RECORD_ID"]; ?>" style="display:none;" onclick="javascript:$('#image<?php print $tmp["RECORD_ID"]; ?>').hide(); flags[<?php print $tmp["RECORD_ID"]; ?>] = false;"><img src="http://gtl.jp/asp/tabecomu/qr_img/php/qr_img.php?d=item<?php print $tmp["RECORD_ID"]; ?>"></div>
<?php
	}
?>
</div>
<?php
		$db->disconnect();
	//}
?>
