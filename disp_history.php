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

			$item_data = $db->getAll( "select t1.*, t2.USER_NAME from proc_data as t1 left join user_data as t2 on t1.USER_ID=t2.RECORD_ID where t1.PROC_FLAG=0 order by ENTRY_TIME DESC limit 5", DB_FETCHMODE_ASSOC );

			$db->disconnect();

		$data["DATE"] = date( "Y-m-d H:i:s" );
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
select { font-size:20px; }
</style>
<div style="width:100%;text-align:center;margin-top:20px;">

	<div style="margin-bottom:10px;">
		企業/団体様：<select>
		<option>指定しない</option>
		<option>○○○○株式会社</option>
		<option>○○○○製作所</option>
		</select>
	</div>

	<div>
		開始：
		<select id="TIME_FROM_YEAR" name="TIME_FROM_YEAR">
		<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( "2015" == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_FROM_MONTH" name="TIME_FROM_MONTH">
		<?php for( $ct = 0; $ct < 13; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($data["DATE"]) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_FROM_DAY" name="TIME_FROM_DAY">
		<?php for( $ct = 0; $ct < 32; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($data["DATE"]) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>より　<br />
		終了：
		<select id="TIME_FROM_YEAR" name="TIME_FROM_YEAR">
		<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( date("Y", strtotime($data["DATE"]) ) == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_FROM_MONTH" name="TIME_FROM_MONTH">
		<?php for( $ct = 0; $ct < 13; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($data["DATE"]) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_FROM_DAY" name="TIME_FROM_DAY">
		<?php for( $ct = 0; $ct < 32; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($data["DATE"]) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>まで　
		<br />
	</div>

	<button class="btn" style="width:300px;margin-top:14px;height:40px;font-size:20px;" onclick="javascript:$('.iframe')[0].contentDocument.location.reload(true);">検索</button><br />

	<table border="0" align="center" style="font-size:20px; width:300px;margin-top:10px;">
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">合計</td>
	<td style="border-bottom: 1px black dotted;width:50%;text-align:right;">125,800</td>
	</tr>
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">割引額</td>
	<td style="border-bottom: 1px black dotted;width:50%;text-align:right;">19,200</td>
	</tr>
	</table>

	<table border="0" align="center" style="font-size:20px;margin-top:10px;">
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">日時</td>
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">お名前</td>
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">総額</td>
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">割引</td>
	</tr>
	<?php
		reset( $item_data );
		foreach( $item_data as $tmp ){
	?>
	<tr style="border-bottom: 1px gray dotted;">
	<td style="border-bottom: 1px gray dotted;"><?php print date( "Y/m/d H:i", strtotime( $tmp["ENTRY_TIME"] ) ); ?></td>
	<td style="border-bottom: 1px gray dotted;"><?php print $tmp["USER_NAME"]; ?></td>
	<td style="border-bottom: 1px gray dotted;text-align:right;"><?php print number_format( $tmp["TOTAL_PRICE"] ); ?></td>
	<td style="border-bottom: 1px gray dotted;text-align:right;"><?php print number_format( $tmp["PRICE"] ); ?></td>
	</tr>
	<?php
		}
	?>
	</table>

	<table border="0" align="center" style="font-size:20px; width:300px;">
	<tr>
	<td style="width:40%;text-align:left;"><button class="btn" style="width:120px;margin-top:10px;height:40px;font-size:20px;" onclick="javascript:$('.iframe')[0].contentDocument.location.reload(true);">次の5件</button></td>
	<td style="width:20%;text-align:center;padding:3px;">1/3</td>
	<td style="width:40%;text-align:right;"><button class="btn" style="width:120px;margin-top:10px;height:40px;font-size:20px;" onclick="javascript:$('.iframe')[0].contentDocument.location.reload(true);">前の5件</button></td>
	</tr>
	</table>

	<button class="btn" style="width:300px;margin-top:10px;height:40px;font-size:20px;" onclick="javascript:historyBack();">戻る</button><br />
</div>
<?php
		$db->disconnect();
	//}
?>
