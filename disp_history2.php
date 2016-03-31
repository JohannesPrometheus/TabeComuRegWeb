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
		
		// セレクトボックス用データ取得
		$query = "SELECT * FROM `order` GROUP BY company_code";
		$companyData = $db->getAll($query, DB_FETCHMODE_ASSOC);
		
		$companyId = "";
		$startTime = "";
		$endTime = "";
		
		
		if(isset($vars['companyBox'])) {
			if($vars['companyBox'] != "指定しない"){
				$companyId = $vars['companyBox'];
			}
			$startTime = $vars['TIME_START_YEAR'] . "-" . $vars['TIME_START_MONTH'] . "-" . $vars['TIME_START_DAY'];
			$endTime = $vars['TIME_END_YEAR'] . "-" . $vars['TIME_END_MONTH'] . "-" . $vars['TIME_END_DAY'];
		}

		$query = "SELECT * FROM `order`";
		if($startTime != ""){
			$query .= " WHERE shop_code='".$vars["shop_code"]."' AND order_day BETWEEN '" . $startTime . "' AND '" . $endTime."'";
			if($companyId != ""){
				$query .= " AND company_code = '" . $companyId . "'";
			}
		}
		$query .= " order by order_day desc";

		$allData = $db->getAll($query, DB_FETCHMODE_ASSOC);

		$total = 0;
		$companyPay = 0;
		foreach( $allData as $tmp ){
			$total += $tmp["order_company_pay"] + $tmp["order_salary_pay"] + $tmp["order_pay"];
			$companyPay += $tmp["order_company_pay"];
		}
		reset( $allData );

		// ページングの設定
		$params = array(
				"perPage"=>5,
				"itemData" => $allData
		);
		
		$pager = Pager::factory($params);
		$navi = $pager -> getLinks();
		$item_data = $pager->GetPageData();
		$db->disconnect();

		if( $startTime == "" ) $startTime = date("Y-m-d", strtotime( date( "Y-m-d" )." - 1year" ) );
		if( $endTime == "" ) $endTime = date( "Y-m-d" );
?>
<script type="text/javascript">
function ifOpen(url){
	$('.iframe')[0].open(url,'_self');
}
</script>
<style type="text/css">
select { font-size:20px; }
</style>
<div style="width:100%;text-align:center;margin-top:20px;">

	<form id="form1" name="form1" method="post" action="http://gtl.jp/asp/tabecomu2/disp_history.php" target="history_window">
	<input type="hidden" id="shop_code" name="shop_code" value="<?php print $vars["shop_code"]; ?>">
	<div style="margin-bottom:10px;">
<?php
	if( false ){
?>
		企業/団体様：<select id="companyBox" name="companyBox">
		<option>指定しない</option>
		<?php
			foreach($companyData as $value){
				$select = "";
				if($companyId == $value['company_code']){ 
					$select = "selected=\"selected\"";
				}
		?>
			<option value="<?php echo $value['company_code'] ?>" <?php echo $select ?>><?php echo $value['company_name'] ?></option>
		<?php
			}
		?>
		</select>
<?php
	}
?>
	</div>

	<div>
		開始：
		<select id="TIME_START_YEAR" name="TIME_START_YEAR">
		<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( date("Y", strtotime($startTime) ) == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_START_MONTH" name="TIME_START_MONTH">
		<?php for( $ct = 1; $ct < 13; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($startTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_START_DAY" name="TIME_START_DAY">
		<?php for( $ct = 1; $ct < 32; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($startTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>より<br />
		終了：
		<select id="TIME_END_YEAR" name="TIME_END_YEAR">
		<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( date("Y", strtotime($endTime) ) == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_END_MONTH" name="TIME_END_MONTH">
		<?php for( $ct = 1; $ct < 13; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($endTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>/
		<select id="TIME_END_DAY" name="TIME_END_DAY">
		<?php for( $ct = 1; $ct < 32; $ct ++  ){ ?>
		<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($endTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
		<?php } ?>
		</select>まで<br />
	</div>
	<button class="btn" style="width:300px;margin-top:14px;height:40px;font-size:20px;" type="submit">検索</button><br />
	</form>

	<table border="0" align="center" style="font-size:20px; width:300px;margin-top:10px;">
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">合計</td>
	<td style="border-bottom: 1px black dotted;width:50%;text-align:right;"><?php print number_format( $total ); ?></td>
	</tr>
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:2px;text-align:center;">割引額</td>
	<td style="border-bottom: 1px black dotted;width:50%;text-align:right;"><?php print number_format( $companyPay ); ?></td>
	</tr>
	</table>

	<table border="0" align="center" style="font-size:16px;margin-top:10px;padding:6px;">
	<tr style="border: 1px gray dotted;">
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">日時</td>
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">決済ID</td>
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">総額</td>
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">法人</td>
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">天引</td>
	<td style="background-color:#CCCCCC;padding:6px;text-align:center;">現金</td>
	</tr>
	<?php
		//reset( $item_data );
		foreach($item_data as $tmp){
	?>
	<tr style="border-bottom: 1px gray dotted;">
	<td style="border-bottom: 1px gray dotted;padding:6px;"><?php print date( "y/m/d H:i", strtotime( $tmp["order_day"] ) ); ?></td>
	<td style="border-bottom: 1px gray dotted;padding:6px;"><?php print sprintf( "%06d", $tmp["order_id"] ); ?></td>
	<td style="border-bottom: 1px gray dotted;padding:6px;text-align:right;"><?php print number_format( $tmp["order_company_pay"] + $tmp["order_salary_pay"] + $tmp["order_pay"] ); ?></td>
	<td style="border-bottom: 1px gray dotted;padding:6px;text-align:right;"><?php print number_format( $tmp["order_company_pay"] ); ?></td>
	<td style="border-bottom: 1px gray dotted;padding:6px;text-align:right;"><?php print number_format( $tmp["order_salary_pay"] ); ?></td>
	<td style="border-bottom: 1px gray dotted;padding:6px;text-align:right;"><?php print number_format( $tmp["order_pay"] ); ?></td>
	</tr>
	<?php
		}
	?>
	</table>

	<table border="0" align="center" style="font-size:20px; width:300px;">
	<tr>
	<td style="width:40%;text-align:left;"><button class="btn" style="width:120px;margin-top:10px;height:40px;font-size:20px;" onclick="javascript:window.open('disp_history.php?<?php reset( $vars ); while( list( $key, $val ) = each( $vars ) ) { if( $key != "pageID" ){ print "&".$key."=".$val; } } ?>&pageID=<?php print $pager->getPreviousPageID(); ?>','_self');">前の5件</button></td>
	<td style="width:20%;text-align:center;padding:3px;"><?php print $pager->getCurrentPageID(); ?>/<?php print $pager->numPages(); ?></td>
	<td style="width:40%;text-align:right;"><button class="btn" style="width:120px;margin-top:10px;height:40px;font-size:20px;" onclick="javascript:window.open('disp_history.php?<?php reset( $vars ); while( list( $key, $val ) = each( $vars ) ) { if( $key != "pageID" ){ print "&".$key."=".$val; } } ?>&pageID=<?php print $pager->getNextPageID(); ?>','_self');">次の5件</button></td>
	</tr>
	</table>
</div>
<?php
		$db->disconnect();
	}
?>
