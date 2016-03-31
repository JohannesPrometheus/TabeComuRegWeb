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

		$PAGE_ROWS = 10;

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
		}
		if(isset($vars['TIME_START_YEAR'])) {
			$startTime = $vars['TIME_START_YEAR'] . "-" . $vars['TIME_START_MONTH'] . "-" . $vars['TIME_START_DAY']." 00:00:00";
		}
		if(isset($vars['TIME_END_YEAR'])) {
			$endTime = $vars['TIME_END_YEAR'] . "-" . $vars['TIME_END_MONTH'] . "-" . $vars['TIME_END_DAY']." 23:59:59";
		}
		if( $startTime == "" ){
			$startTime = date( "Y-m-d" )." 00:00:00";
		}
		if( $endTime == "" ) $endTime = date( "Y-m-d" )." 23:59:59";

		$query = "SELECT * FROM `shop` WHERE shop_code='".$vars["h_shop_code"]."'";
		$shop_data = $db->getRow( $query, DB_FETCHMODE_ASSOC );

		$query = "SELECT * FROM `order` WHERE shop_code='".$vars["h_shop_code"]."'";
		if($startTime != ""){
			$query .= "  AND order_day BETWEEN '" . $startTime . "' AND '" . $endTime."'";
			if($companyId != ""){
				$query .= " AND company_code = '" . $companyId . "'";
			}
		}
		$query .= " order by order_day desc";

		$allData = $db->getAll($query, DB_FETCHMODE_ASSOC);

		$total = 0;
		$urikake = 0;
		foreach( $allData as $tmp ){
			$total += $tmp["order_company_pay"] + $tmp["order_salary_pay"] + $tmp["order_pay"];
			$urikake += $tmp["order_company_pay"] + $tmp["order_salary_pay"];
		}
		reset( $allData );

		// ページングの設定
		$params = array(
				"perPage"=>$PAGE_ROWS,
				"itemData" => $allData
		);
		
		$pager = Pager::factory($params);
		$navi = $pager -> getLinks();
		$item_data = $pager->GetPageData();
		$db->disconnect();

?>
<script type="text/javascript">
function ChangeMonthLastDay(str){
	var year_str	= 'TIME_'+str+'_YEAR';
	var month_str	= 'TIME_'+str+'_MONTH';
	var day_str	= 'TIME_'+str+'_DAY';

	var tmp_year	= $('#'+year_str).val();
	var tmp_month	= $('#'+month_str).val();
	var tmp_day	= $('#'+day_str).val();

	var last_day	= new Date(tmp_year, tmp_month, 0).getDate();

	for(var i=0; i < document.getElementById(day_str).options.length; i++){
		document.getElementById(day_str).options[i] = null;
	}
	var onum = 0;
	var ostr = '';
	for(var i=0; i < last_day; i++){
		onum = i+1;
		ostr = ("0"+onum).slice(-2);
		document.getElementById(day_str).options[i] = new Option(ostr,ostr);
	}
	$('#'+day_str).val(tmp_day);
}
</script>
    
<form id="form_h" name="form_h">
    
    <div class="message_box small_text" style="margin-top:0px; margin-bottom:10px;">
	<div style="middle_text"><?php print $shop_data["shop_name"]; ?>さま</div>
	<div style="middle_text">ご利用履歴</div>

	<select class="little_text" id="TIME_START_YEAR" name="TIME_START_YEAR" onchange="ChangeMonthLastDay('START');">
	<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( date("Y", strtotime($startTime) ) == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
	<?php } ?>
	</select>/
	<select class="little_text" id="TIME_START_MONTH" name="TIME_START_MONTH" onchange="ChangeMonthLastDay('START');">
	<?php for( $ct = 1; $ct < 13; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($startTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
	<?php } ?>
	</select>/
	<select class="little_text" id="TIME_START_DAY" name="TIME_START_DAY">
	<?php for( $ct = 1; $ct < 32; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($startTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
	<?php } ?>
	</select>より<br />
	<select class="little_text" id="TIME_END_YEAR" name="TIME_END_YEAR" onchange="ChangeMonthLastDay('END');">
	<?php for( $ct = 2014; $ct < 2017; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%04d", $ct ); ?>"<?php if( date("Y", strtotime($endTime) ) == sprintf( "%04d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%04d", $ct ); ?></option>
	<?php } ?>
	</select>/
	<select class="little_text" id="TIME_END_MONTH" name="TIME_END_MONTH" onchange="ChangeMonthLastDay('END');">
	<?php for( $ct = 1; $ct < 13; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("m", strtotime($endTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
	<?php } ?>
	</select>/
	<select class="little_text" id="TIME_END_DAY" name="TIME_END_DAY">
	<?php for( $ct = 1; $ct < 32; $ct ++  ){ ?>
	<option value="<?php print sprintf( "%02d", $ct ); ?>"<?php if( date("d", strtotime($endTime) ) == sprintf( "%02d", $ct ) ){ print " selected"; } ?>><?php print sprintf( "%02d", $ct ); ?></option>
	<?php } ?>
	</select>まで
    </div>
        
    
    <div style="width:100%;text-align:center;margin-bottom:10px;margin-top:0px;">
      <button class="btn btn_middle btn_large_blue" type="button" onclick="javascript:historyLoad(0,1);">ご利用履歴表示</button>
    </div>
</form>

    <div class="message_box" style="margin-bottom:10px;">
      <table class="statics_table" style="margin-bottom:0px;">
        <tr>
        <th>ご利用件数</th>
        <td><?php print number_format( count( $allData ) ); ?> 件</th>
        </tr>
        <tr>
        <th>ご利用金額総計</th>
        <td><?php print number_format( $total ); ?> 円</th>
        </tr>
        <tr>
          <th>売掛計</th>
          <td><?php print number_format( $urikake ); ?> 円</td>
        </tr>
      </table>
    </div>
    
    <div style="margin-top:0px;margin-bottom:0px;">
      <table class="history_table tiny_text" style="margin-top:0px;margin-bottom:0px;">
        <tr>
        <th style="padding:4px;width:100px;text-align:center;">日時</th>
        <th style="padding:4px;width:40px;">決済ID</th>
        <th style="padding:4px;width:60px;">ご利用金額</th>
        <th style="padding:4px;width:40px;">売掛</th>
        <th style="padding:4px;width:40px;">現金</th>
        </tr>
	<?php
		reset( $item_data );

		foreach($item_data as $tmp){
	?>
        <tr>
          <td style="padding:4px;width:100px;text-align:center;"><?php print date( "y/m/d H:i", strtotime( $tmp["order_day"] ) ); ?></td>
          <td style="padding:4px;width:40px;text-align:center;"><?php print sprintf( "%06d", $tmp["order_id"] ); ?></td>
          <td style="padding:4px;width:60px;"><?php print number_format( $tmp["order_company_pay"] + $tmp["order_salary_pay"] + $tmp["order_pay"] ); ?></td>
          <td style="padding:4px;width:40px;"><?php print number_format( $tmp["order_company_pay"] + $tmp["order_salary_pay"] ); ?></td>
          <td style="padding:4px;width:40px;"><?php print number_format( $tmp["order_pay"] ); ?></td>
        </tr>
	<?php
		}
	?>
      </table>
      
      <div style="width:100%; margin-top:10px; margin-bottom:0px;">
<?php if( $pager->isFirstPage( $pager->getPreviousPageID() ) ){ $str = "hidden"; }else{ $str = "visible"; } ?>
          <span style="visibility:<?php print $str; ?>"><button class="btn btn_pg" onclick="javascript:historyLoad(0,<?php print $pager->getPreviousPageID(); ?>);">前の<?php print $PAGE_ROWS; ?>件</button></span>
          <span><?php print $pager->getCurrentPageID(); ?>/<?php print $pager->numPages(); ?></span>
<?php if( $pager->isLastPage( $pager->getNextPageID() ) ){ $str = "hidden"; }else{ $str = "visible"; } ?>
          <span style="visibility:<?php print $str; ?>"><button class="btn btn_pg" onclick="javascript:historyLoad(0,<?php print $pager->getNextPageID(); ?>);">次の<?php print $PAGE_ROWS; ?>件</button></span>
      </div>
       
</div>
<script>
ChangeMonthLastDay('START');
ChangeMonthLastDay('END');
</script>
<?php
		$db->disconnect();
	}
?>
