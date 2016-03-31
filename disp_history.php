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

			$item_data = $db->getAll( "select t1.*, t2.USER_NAME from proc_data as t1 left join user_data as t2 on t1.USER_ID=t2.RECORD_ID where t1.PROC_FLAG=0 order by ENTRY_TIME DESC limit 10", DB_FETCHMODE_ASSOC );

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
<div style="width:100%;text-align:center;margin-top:40px;">
<table border="0" align="center" style="font-size:20px;">
<tr style="border: 1px gray dotted;">
<td style="border-bottom: 1px black dotted;">日時</td>
<td style="border-bottom: 1px black dotted;">お名前</td>
<td style="border-bottom: 1px black dotted;">総額</td>
<td style="border-bottom: 1px black dotted;">割引</td>
</tr>
<?php
	reset( $item_data );
	foreach( $item_data as $tmp ){
?>
<tr style="border-bottom: 1px gray dotted;">
<td style="border-bottom: 1px gray dotted;"><?php print date( "Y/m/d H:i", strtotime( $tmp["ENTRY_TIME"] ) ); ?></td>
<td style="border-bottom: 1px gray dotted;"><?php print $tmp["USER_NAME"]; ?></td>
<td style="border-bottom: 1px gray dotted;"><?php print number_format( $tmp["TOTAL_PRICE"] ); ?></td>
<td style="border-bottom: 1px gray dotted;"><?php print number_format( $tmp["PRICE"] ); ?></td>
</tr>
<?php
	}
?>
</table>
<button class="btn" style="width:300px;margin-top:40px;height:40px;font-size:20px;" onclick="javascript:historyBack();">戻る</button><br />
</div>
<?php
		$db->disconnect();
	//}
?>
