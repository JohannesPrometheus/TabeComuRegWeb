<?php
define( "FILE_SYSTEM_ROOT", "/home/sites/heteml/users/g/e/n/genkaido/web/gtl.jp/asp/tabecomu/");
define("DOCUMENT_ROOT", "/asp/tabecomu/");
define("URL_ROOT", "http://gtl.jp/asp/tabecomu/");

define("MAIL_SENDER", "info@domainname.com");
define("CMS_CHARACTER_SET", "set names utf8;" );

define("CMS_ADMIN_SESSION", "TABECOMUSTAFF");

$ADMIN_MAIL_ADDRESS = "joda@genkaido.jp";

$dsnDB = "mysql://_tabecomu3:ginnosuke@mysql510.heteml.jp/_tabecomu3";

//----------------------------------------------------	
// Definition of Administrator Authentification 
//----------------------------------------------------	

$dsnAdmin = array( 
	"dsn" => $dsnDB,
	"table" => "shop",
	"usernamecol" => "shop_login_id",
	"passwordcol" => "shop_password",
	"cryptType" => "none"
);

function adminLogin($username,$status){
	header( "Location:".DOCUMENT_ROOT."manager/admin_login.php?status=".$status );
}
//----------------------------------------------------	
// Definition of Member Authentification 
//----------------------------------------------------	
define("CMS_MEMBER_SESSION", "TABECOMUMEMBER");

$dsnMember = array( 
	"dsn" => $dsnDB,
	"table" => "shop",
	"usernamecol" => "shop_login_id",
	"passwordcol" => "shop_password",
	"cryptType" => "none",
        "db_fields"=>"*"
);

function memberLogin($username,$status){
	header( "Location:http://gtl.jp/asp/tabecomu2/login.php" );
}
//----------------------------------------------------	
?>