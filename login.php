<?php
	$pathForRoot = "./";
	require_once $pathForRoot."tools/special_conf.php";
	require_once $pathForRoot."tools/system_tools.php";
?>
<html>
<body style="text-align:center;">
<form name="form3" id="form3" method="POST" action="<?php print URL_ROOT; ?>/login_proc.php">
<input type="text" name="username" id="username"><br />
<br />
<input type="password" name="password" id="password"><br />
<br />
<input type="submit" value="ログイン">
</form>
</body>
</html>
