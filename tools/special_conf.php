<?php
define( "FILE_SYSTEM_ROOT", "/home/sites/heteml/users/g/e/n/genkaido/web/gtl.jp/asp/tabecomu/");
define("DOCUMENT_ROOT", "/asp/tabecomu/");
define("URL_ROOT", "http://gtl.jp/asp/tabecomu/");

define("MAIL_SENDER", "info@domainname.com");
define("CMS_CHARACTER_SET", "set names utf8;" );

define("CMS_ADMIN_SESSION", "TABECOMUSTAFF");

$ADMIN_MAIL_ADDRESS = "joda@genkaido.jp";

$dsnDB = "mysql://_tabecomu:tabecomu@mysql509.heteml.jp/_tabecomu";

//----------------------------------------------------	
// Definition of Administrator Authentification 
//----------------------------------------------------	

$dsnAdmin = array( 
	"dsn" => $dsnDB,
	"table" => "staff_data",
	"usernamecol" => "STAFF_ID",
	"passwordcol" => "STAFF_PASS_MD5"
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
	"table" => "client_data",
	"usernamecol" => "LOGIN_ID",
	"passwordcol" => "LOGIN_PASS_MD5",
        "db_fields"=>"*"
);

function memberLogin($username,$status){
	header( "Location:./member_login.php?status=".$status );
}
//----------------------------------------------------	
?>