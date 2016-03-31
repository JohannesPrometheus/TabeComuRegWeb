<?php
function loginFunction($username, $status){
	global $pathForRoot;
	require_once( $pathForRoot."login.php" );
}

function loginUser($username, $status){
	global $pathForRoot;
	require_once( $pathForRoot."login_error.php" );
}
?>