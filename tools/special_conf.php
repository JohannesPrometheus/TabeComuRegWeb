<?php
define("DIST_NAME", "tabecomu4n");
define("FILE_SYSTEM_ROOT", "/home/sites/heteml/users/g/e/n/genkaido/web/gtl.jp/asp/".DIST_NAME."/");
define("DOCUMENT_ROOT", "/asp/".DIST_NAME."/");
define("URL_ROOT", "http://gtl.jp/asp/".DIST_NAME."/");

define("MAIL_SENDER", "info@domainname.com");
define("CMS_CHARACTER_SET", "set names utf8;" );

define("CMS_ADMIN_SESSION", "TABECOMUSTAFF");

$ADMIN_MAIL_ADDRESS = "joda@genkaido.jp";

$dsnDB = "mysql://_tabecomu3:ginnosuke@mysql510.heteml.jp/_tabecomu3";
//$dsnDB = "mysql://wakuwaku:wakuwaku2014PW@54.248.228.7/wakuwakudiningkindle";

//----------------------------------------------------	
// Definition of Member Authentification 
//----------------------------------------------------	
define("CMS_MEMBER_SESSION", "TABECOMUMEMBER");

$dsnMember = array( 
	"dsn" => $dsnDB,
	"table" => "shop",
	"usernamecol" => "shop_code",
	"passwordcol" => "shop_password",
	"cryptType" => "none",
        "db_fields"=>"*"
);

function memberLogin($username,$status){
	//header( "Location:http://gtl.jp/asp/".DIST_NAME."/login.php?stats=$status" );
}
//----------------------------------------------------	
?>