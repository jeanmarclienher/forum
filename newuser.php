<?php
	if ((isset($HTTP_GET_VARS['lang']) && $HTTP_GET_VARS['lang'] == "en") ||
	(isset($HTTP_POST_VARS['lang']) && $HTTP_POST_VARS['lang'] == "en")) 
	{
		include "lang.en.php";
		$lang['lang']="en";
	} else { 
		include "lang.fr.php";
		$lang['lang']="fr";
	}
	include "common.php";
	
	check_ip();
?><?php
	
	$user['email'] =  "";
	$user['pseudo'] = "";
	$pre = "";
	$is_ok = 0;
	if (isset($HTTP_POST_VARS['email'])) {
			$user['email'] =  stripslashes($HTTP_POST_VARS['email']);
	}
	if (isset($HTTP_POST_VARS['pseudo']) && $HTTP_POST_VARS['pseudo'] != "") {
			$user['pseudo'] =  stripslashes($HTTP_POST_VARS['pseudo']);
			check_text($user['pseudo']);
			
			$p = "users";
			if (!is_dir($p)) {
				mkdir($p, 0777);
			}
			$f = "users/" . urlencode(stripslashes($HTTP_POST_VARS['pseudo'])) . ".php";
			if (is_file($f)) {
				$pre .= $lang['user_exists'];
				$user['pseudo'] = "";
			} 
			
			if (!isset($HTTP_POST_VARS['email']) ||
				!strstr($HTTP_POST_VARS['email'], '@') ||
				!strstr($HTTP_POST_VARS['email'], '.'))
			{
				$pre .= $lang['invalid_email'];
			}
			
			if (!isset($HTTP_POST_VARS['passwd']) ||
				!isset($HTTP_POST_VARS['passwd1']) ||
				strlen($HTTP_POST_VARS['passwd']) < 6 ||
				$HTTP_POST_VARS['passwd'] != 
				$HTTP_POST_VARS['passwd1'])  
			{
				$pre .= $lang['invalid_password'];
			}
			
			if ($pre == "") {
				$p = "mails";
				if (!is_dir($p)) mkdir($p, 0777);
				$d = date("Ymd", time());
				$p .= "/" . $d;
				if (!is_dir($p)) mkdir($p, 0777);
				$f1 = $p . "/" . $_SERVER['REMOTE_ADDR'] . ".php";
				if (!is_file($f1)) {
					$handle = fopen($f1, "wb");
					fwrite($handle, "<?php\n");
					foreach($_SERVER as $k=>$v) {
   						fwrite($handle, "\$var['" . $k . "']='" . addslashes($v) . "';\n");
   					}
					foreach($HTTP_POST_VARS as $k=>$v) {
   						//fwrite($handle, "\$var['" . $k . "']='" . addslashes($v) . "';\n");
   					}
					fwrite($handle, "\$var['email']='" .  addslashes($HTTP_POST_VARS['email']) . "';\n");
					fwrite($handle, "?>\n");
					fclose($handle);
				} else {
					$pre .= $lang['rejected_email'];
				}
				// cleanup directory
				$p = "mails";
	   			if ($dh = opendir($p)) {
	       			while (($file = readdir($dh)) !== false) {
	       	 			if ($file != "." && $file != ".." && $file != $d &&
	       	 				is_dir($p . "/" . $file)) 
	       	 			{
	       	 				if ($dh1 = opendir($p . "/" . $file)) {
	       	 					while (($file1 = readdir($dh1)) !== false) {
	       	 						if ($file1 != "." && $file1 != "..") {
	       	 							unlink($p . "/" . $file . "/" . $file1);
	       	 						}
	       	 					}
	       	 				}
							@rmdir($p . "/" . $file);
	       	 			}
	       	 		}
	       		}
	       		closedir($dh);
			}
			
			if ($pre == "") {
				$user['pseudo'] =  stripslashes($HTTP_POST_VARS['pseudo']);
				$user['email'] =  stripslashes($HTTP_POST_VARS['email']);
				$user['passwd'] =  crypt($HTTP_POST_VARS['passwd']);
				$user['verify'] = "OKD". mt_rand() . mt_rand() . "X";
				$user['remote_addr'] = $_SERVER['REMOTE_ADDR'];
				$user['remote_port'] = $_SERVER['REMOTE_PORT'];
				$user['pid'] = sprintf("%08X", getmypid());
				$user['time'] = sprintf("%d", time());
				$user['lang'] = $lang['lang'];
				$user['last_login_cookie'] = "none";
				$user['last_login_time'] = "0";
				$user['last_login_ip'] = "0";
				$user['group'] = "user";

				dumptofile($user, $f);
				
				$from = "From: \"". $HTTP_POST_VARS['pseudo']."\" <". "noreply@oksidizer.com" . ">";
				$body = $lang['verify_body1'] . "\nhttp://www.oksidizer.com/forum/verify.php?";
				$body .= "pseudo=" . urlencode($user['pseudo']);
				$body .= "&verify=" .  urlencode($user['verify']) . "\n" . $lang['verify_body2'];
				$body .= "\n\n Sent by IP : " . $_SERVER['REMOTE_ADDR'];
				mb_language('Neutral');
				mb_internal_encoding("UTF-8");
				mb_http_input("UTF-8");
				mb_http_output("UTF-8");
				$ret = mb_send_mail($user['email'],  $lang['verify_subject'],  $body, $from);
						
				$pre =  $lang['verify_sent'];
				$is_ok = 1;
			}
	}

?>

<html>

<head><meta http-equiv="Content-Language" content="<?php echo $lang['Content-Language']; ?>">	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>oKsidiZer : Forum</title>
<style><!--
	* { font-family: sans-serif;}
//--></style>
</head>
<body bgcolor="#333333" >

	<table border="0" width="100%" height="300">
	<tr>
	
	<!-- directory tree -->
	<td align="left" valign="top" height="24">
		<a href="http://www.oksidizer.com"><font color="#33CCFF" face="arial">www.oksidizer.com</font></a>
		<font color="#FFFFFF" face="arial"> / </font>
		<a href="http://www.oksidizer.com/forum"><font color="#33CCFF" face="arial">Forum</font></a>
		<font color="#FFFFFF" face="arial"> / </font>
			<a href="http://www.oksidizer.com/forum/newuser.php"><font color="#33CCFF" face="arial">newuser</font></a>
		<font color="#FFFFFF" face="arial"> / </font>

	</td>
	</tr><tr>


		<td align="center"><img alt="oKsidiZer" src="../logo.gif" width="491" height="94" /><p>
			<font size="5" color="#C0C0C0"><b>Forum</b></font></p>
			<p><font size="4" color="#FFFFFF"><b><?php echo $lang['user_data']; ?></b></font></td>
	</tr>
</table>
<p align="center"><a href="newuser.php">
<img border="0" src="../fr.gif" width="30" height="15"></a>&nbsp;&nbsp;&nbsp;
<a href="newuser.php?lang=en">
<img border="0" src="../en.gif" width="30" height="15"></a></p>

<center><font face="Arial" color="#FFFFFF">
<b><?php echo $pre; ?></b>
</font>
</center>

<?php if ($is_ok == 1) { ?>
<p>&nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>
</html>
<?php 
	exit(0);
} ?>


<center>
<form method="POST" action="newuser.php">
	<table border="0">
		<tr>
			<td><font face="Arial" color="#FFFFFF">
	<?php echo $lang['pseudo']; ?> (*)</font> </td>
			<td>
	<input type="text" name="pseudo" size="30" value="<?php echo  htmlspecialchars($user['pseudo']); ?>" ></td>
		</tr>
		<tr>
			<td><font face="Arial" color="#FFFFFF">
			<?php echo$lang['email']; ?> (*)</font></td>
			<td>
	<input type="text" name="email" size="30" value="<?php echo  htmlspecialchars($user['email']); ?>" ></td>
		</tr>
		<tr>
			<td><font color="#FFFFFF"><?php echo $lang['passwd']; ?> (*)</font></td>
			<td>
	<input type="password" name="passwd" size="30"></td>
		</tr>
		<tr>
			<td><font color="#FFFFFF"><?php echo $lang['passwd1']; ?> (*)</font></td>
			<td>
	<input type="password" name="passwd1" size="30"></td>
		</tr>
	</table>
	<p><font color="#FFFFFF">(*) = <?php echo $lang['required_data']; ?> !!</font></p>
	<p><input type="submit" value="<?php echo $lang['send']; ?>" name="B1">
	<input type="hidden" value="<?php echo $lang['lang']; ?>" name="lang">
</form>
</center>
<p>&nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>