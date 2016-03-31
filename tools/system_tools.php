<?php
//-------------------------------------------------------------------------------------
// System Tools ver.3.0
//-------------------------------------------------------------------------------------

function GetFormVars( $debug=false ){
	$vars = array();
	$method = "POST";

	$vars = $_POST;
	if( !( count( $vars ) > 0 ) ){
		$method = "GET";
		$vars = $_GET;
	}

	reset ( $vars );
	while (list ($key, $val) = each ($vars)) {
		if( $method == "GET" )	$val = geted_string( $val );
		if( $method == "POST" )	$val = posted_string( $val );
		
		$vars[$key] = trim( $val );
		$vars[$key] = Sanitizing( $vars[$key] );

		if( $debug ) print $method."ED:$key:$val:<BR>\n";
	}
	return( $vars );
}

function Sanitizing( $string ){
	// $string = htmlspecialchars( $string, ENT_QUOTES );
	return( $string );
}

function SaveFormFiles( $uid, $save_dir, $debug ){
	global $_FILES;

	$vars = $_FILES;
	
	$keys = array_keys( $vars );

	$ct = 0;

	foreach ( $keys as $tmp ){

		if( $vars[$tmp]["name"] != "" ){

			$filename_parts	= explode( ".", $vars[$tmp]["name"]);
			$parts_num		= count( $filename_parts );
			$tmp_ext		= ".".$filename_parts[ $parts_num-1 ];
			$save_file_name = $uid."_".time()."_".$ct.$tmp_ext;

			copy( $vars[$tmp]["tmp_name"], $save_dir.$save_file_name );

			$save_data[$tmp]["original_file_name"]	= $vars[$tmp]["name"];
			$save_data[$tmp]["save_file_name"]	= $save_file_name;

			$ct++;

			if( $debug ){
				print "FILE:".$vars[$tmp]["name"]."(".$vars[$tmp]["tmp_name"].") as ".$tmp." saved to ".$save_file_name."<BR>";
			}
		}
	}

	return( $save_data );
}

function FileGetProc( $name_array, $fdata, $data ){
	while( list( $key, $tmp ) = each( $name_array ) ){
		if( is_array( $fdata[$tmp] ) && $fdata[$tmp]["save_file_name"] != "" ){
			$data[$tmp]		= $fdata[$tmp]["save_file_name"];
			$data[$tmp."_NAME"]	= $fdata[$tmp]["original_file_name"];
		}else{
			if( $data[$tmp."_OLD"] != "" ){
				$data[$tmp]		= $data[$tmp."_OLD"];
				$data[$tmp."_NAME"]	= $data[$tmp."_NAME_OLD"];
			}else{
				$data[$tmp] = "";
				$data[$tmp."_NAME"] = "";
			}
		}
		unset( $data[$tmp."_OLD"] );
		unset( $data[$tmp."_NAME_OLD"] );
	}
	return( $data );
}

function MakeFileInput( $name, $data, $size, $error_message="Delete this file, OK ?" ){
	if( $data[$name] !="" ){
		print "<input type=\"hidden\" name=\"".$name."_OLD\" id=\"".$name."_OLD\" value=\"".$data[$name]."\">\n";
		print "<input type=\"hidden\" name=\"".$name."_NAME_OLD\" id=\"".$name."_NAME_OLD\" value=\"".$data[$name."_NAME"]."\">\n";
		print "<input type=\"text\" name=\"".$name."_NAME\" id=\"".$name."_NAME\" value=\"".$data[$name."_NAME"]."\" size=\"".$size."\" disabled>\n";
		print "<input type=\"button\" name=\"DELETE_".$name."\" id=\"DELETE_".$name."\"  value=\"DELETE FILE\" onclick=\"javascript:delete".$name."();\"><br />\n";
	}

	print "<input type=\"FILE\" id=\"".$name."\" name=\"".$name."\">\n";

	if( $data[$name] !="" ){
		print "<script type=\"text/javascript\">\n";
		print "<!--\n";
		print "function delete".$name."(){\n";
		print "	if( confirm( \"".$error_message."\") ){\n";
		print "		document.form1.".$name."_NAME_OLD.value	= \"\";\n";
		print "		document.form1.".$name."_OLD.value		= \"\";\n";
		print "		document.form1.".$name."_NAME.value		= \"\";\n";
		print "	}\n";
		print "}\n";
		print "//-->\n";
		print "</script>\n";
	}
}

function MakeHiddenVars( $vars ){
	reset ( $vars );
	while (list ($key, $val) = each ($vars)) {
    		print "<INPUT type=\"hidden\" name=\"".$key."\" value=\"".for_post_string( $val )."\">\n";
	}
}


function geted_string( $string ){
	return( stripslashes( $string ) );
}

function posted_string( $string ){
	return( stripslashes( $string ) );
}

function for_get_string( $string ){
	return( $string );
}

function for_post_string( $string ){
	return( $string );
}

function GetMyself(){
	$tmp_str = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
	return( $tmp_str );
}

function GetRandomStr($len) {
    $token = array_merge(
      range("A","Z"),
      range("a","z"),
      range("1","9"));
    for($i = 0; $i < $len; $i++) {
        $str .= $token[rand(0,count($token))];
    }
    return $str;
}

class ConfigFile
{
	var $numElements;
	var $configs;

	function ConfigFile( $ConfigFileName ){
		
		$this->configs = array();

		$ct = 0;

		$fhdr = fopen( $ConfigFileName, "r" );
			while( $buf = fgets( $fhdr, 2000 ) ){
				$pair = explode( "]", $buf );
				for( $ect = 0; $ect < 2; $ect ++ ){
					$pair[ $ect ]	= str_replace( " ",	"", $pair[ $ect ] ); 
					$pair[ $ect ]	= str_replace( "@",	"", $pair[ $ect ] ); 
					$pair[ $ect ]	= str_replace( "\t",	"", $pair[ $ect ] ); 
					$pair[ $ect ]	= str_replace( "\n",	"", $pair[ $ect ] ); 
					$pair[ $ect ]	= str_replace( "\r",	"", $pair[ $ect ] ); 
					$pair[ $ect ]	= str_replace( "[",	"", $pair[ $ect ] ); 
				}

				$this->configs[ $pair[0] ] = $pair[1];

				$ct ++;
			}
		fclose( $fhdr );

		$this->numElements = $ct;
	}
}

function MakeDateSelect( $name, $style, $default, $space ){
	$ddate = explode( "/", $default );

	$buffer = "";

	$buffer .= "<select name=\"".$name."_YEAR\" class=\"".$style."\">\n";
	for( $ct = 0; $ct < 10; $ct ++ ){
		$tmp_year = date("Y", strtotime( date( "Y/m/d" )." +".( $ct - 1 )." year ") );
		if( $tmp_year == $ddate[0] ){ $tmp_selected = " selected"; }else{ $tmp_selected = ""; }
		$buffer .=  "<option value=\"".$tmp_year."\"".$tmp_selected.">".$tmp_year."</option>\n";
	}
	$buffer .=  "</select>\n";
	$buffer .=  $space."\n";
	$buffer .=  "<select name=\"".$name."_MONTH\" class=\"".$style."\">\n";
	for( $ct = 0; $ct < 12; $ct ++ ){
		$tmp_month = $ct + 1;
		if( $tmp_month == $ddate[1] ){ $tmp_selected = " selected"; }else{ $tmp_selected = ""; }
		$buffer .=  "<option value=\"".$tmp_month."\"".$tmp_selected.">".$tmp_month."</option>\n";
	}
	$buffer .=  "</select>\n";
	$buffer .=  $space."\n";
	$buffer .=  "<select name=\"".$name."_DAY\" class=\"".$style."\">\n";
	for( $ct = 0; $ct < 31; $ct ++ ){
		$tmp_day = $ct + 1;
		if( $tmp_day == $ddate[2] ){ $tmp_selected = " selected"; }else{ $tmp_selected = ""; }
		$buffer .=  "<option value=\"".$tmp_day."\"".$tmp_selected.">".$tmp_day."</option>\n";
	}
	$buffer .=  "</select>\n";

	return $buffer;
}

function setFile( $files ){
	$flag = false;
	foreach( $files as $tmp ){
		if( $tmp["name"] != "" ) $flag = true;
	}
	return $flag;
}

function IsMail($text) {
    if (preg_match('/^[^0-9][a-zA-Z0-9_\-.]+([.][a-zA-Z0-9_\-.]+)*[@][a-zA-Z0-9_\-.]+([.][a-zA-Z0-9_\-.]+)*[.][a-zA-Z]{2,4}$/', $text)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function IsURL($text) {
    if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $text)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function IsPhoneNo($text) {
	$text = ereg_replace("\(|\)|\+","", $text);
	if(preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $text )){
	    return TRUE;
	} else {
	    return FALSE;
	}
}

function IsAlphanumeric($text) {
    if (preg_match('/^[a-zA-Z0-9 ]+$/', $text)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isImage($path) {
	$tmp = explode( ".", $path );
	$tmp_ext = $tmp[1];
	if(
		$tmp_ext = "gif"	|| $tmp_ext = "GIF"	||
		$tmp_ext = "jpg"	|| $tmp_ext = "JPG"	||
		$tmp_ext = "bmp"	|| $tmp_ext = "BMP"	||
		$tmp_ext = "png"	|| $tmp_ext = "PNG"	||
		$tmp_ext = "jpeg"	|| $tmp_ext = "JPEG"
	){
		return( TRUE );
	}else{
		return( FALSE );
	}
}

function UpfileProc( $vars, $data, $filedata, $old_filedata, $filename, $old_filename, $default ){

	if( is_array( $data[$filedata] ) && $data[$filedata]["save_file_name"] != "" ){

		$vars[$filedata] = $data[$filedata]["save_file_name"];
		$vars[$filename] = $data[$filedata]["original_file_name"];
	}else{

		if( $vars[$old_filedata] != "" ){
			$vars[$filedata] = $vars[$old_filedata];
			$vars[$filename] = $vars[$old_filename];
		}else{
			$vars[$filedata] = $default;
			$vars[$filename] = "";
		}

	}

	unset( $vars[$old_filedata] );
	unset( $vars[$old_filename] );

	return( $vars );
}

?>
