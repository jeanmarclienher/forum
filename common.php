<?php if (0) { ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?php
}

	function check_text($txt) {
		include "reject.php";
		foreach($var as $k=>$v) {
			if (strstr($txt, $v)) {
				exit(0);
			}
		}
		unset($var);	
	}

	function dumptofile($data, $file)
	{
		$handle = fopen($file . ".php", "wb");
		fwrite($handle, "<?php\n");
		foreach($data as $k=>$v) {
			$v = ereg_replace("\\\\", "\\\\", $v); 
			$v = ereg_replace("\"", "\\\"", $v); 
			fwrite($handle, "\$var['" . $k . "']=\"" . $v . "\";\n");
   		}
		fwrite($handle, "?>\n");
		fclose($handle);
		if (is_file($file)) unlink($file);
		@rename(	$file . ".php", $file);
		return 0;
	} 
	
	function check_ip() {
		$ip = $_SERVER['REMOTE_ADDR'];
		include "ip.php";
		foreach($var as $k=>$v) {
			if ($ip == $v) {
				exit(0);
			}
		}
		unset($var);	
	}

	function check_login() 
	{
		global $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $_SERVER, $user;
		
		if (isset($HTTP_POST_VARS['logout'])) {
			foreach($HTTP_COOKIE_VARS as $k=>$v) {
				setcookie (stripslashes($k), "", time() - 3600);
			}
			return;
		}
		
		if (isset($HTTP_COOKIE_VARS)) {
			foreach($HTTP_COOKIE_VARS as $k=>$v) {
				//echo $k . " = " . $v;
				$f = "users/" . stripslashes($k) . ".php";
				if (is_file($f)) {
					include $f;
					if ($var['last_login_cookie'] == stripslashes($v) &&
						$var['last_login_ip'] == $_SERVER['REMOTE_ADDR']) 
					{
						$user = $var;
						unset($var);
						return;
					}
				}
			}
		}
		
		if (isset($HTTP_POST_VARS['pseudo']) &&
			isset($HTTP_POST_VARS['passwd'])) 
		{
			$f = "users/" . urlencode(stripslashes($HTTP_POST_VARS['pseudo'])) . ".php";
			if (is_file($f)) {
				include $f;
				if (crypt($HTTP_POST_VARS['passwd'], $var['passwd']) == $var['passwd']) {
					$user = $var;
					unset($var);
					$user['last_login_cookie'] = "for" . mt_rand();
					$user['last_login_time'] = time();
					$user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
					setcookie(urlencode($user['pseudo']), $user['last_login_cookie']);
					dumptofile($user, $f);
					return;
				}
			}
			include "login_or_new.php";
			exit(0);
		}	
	}
?>
