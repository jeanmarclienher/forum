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
?><?php
	
	$user['email'] =  "";
	$user['pseudo'] = "";
	$pre = "";
	$is_ok = 0;
	if (isset($HTTP_GET_VARS['pseudo']) && $HTTP_GET_VARS['pseudo'] != "" &&
		isset($HTTP_GET_VARS['verify'])) 
	{
			$user['pseudo'] =  stripslashes($HTTP_GET_VARS['pseudo']);
		
			$f = "users/" . urlencode(stripslashes($HTTP_GET_VARS['pseudo'])) . ".php";
			if (is_file($f)) {
				include $f;
				$user = $var;
				unset($var);
				if ($user['verify'] == "ok") {
					$is_ok = 1;
				} else if (stripslashes($HTTP_GET_VARS['verify']) == $user['verify']) {
					$user['verify'] = "ok";
					$user['last_login_cookie'] = "for" . mt_rand();
					$user['last_login_time'] = time();
					$user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
					setcookie(urlencode($user['pseudo']), $user['last_login_cookie']);
					dumptofile($user, $f);
					$is_ok = 1;
				} 
			} 
	}			
	foreach($HTTP_COOKIE_VARS as $k=>$v) {
		//echo "$k : $v <br />\n";
	}

?>

<html>

<head><meta http-equiv="Content-Language" content="<?php echo $lang['Content-Language']; ?>">	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
if ($is_ok == 1) { 
?>
<meta http-equiv="Refresh" content="3; URL=." >

<?php 
} 
?>

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
			<a href="http://www.oksidizer.com/forum/verify.php"><font color="#33CCFF" face="arial">verify</font></a>
		<font color="#FFFFFF" face="arial"> / </font>

	</td>
	</tr><tr>


		<td align="center"><img alt="oKsidiZer" src="../logo.gif" width="491" height="94" /><p>
			<font size="5" color="#C0C0C0"><b>Forum</b></font></p>
			<p><font size="4" color="#FFFFFF"><b><span lang="fr-ch">vérification 
			d&#39;adresse / E-mail check </span></b></font></td>
	</tr>
</table>


<?php 
if ($is_ok == 1) { 
?>
<p>&nbsp;<p align="center"><b><font face="Arial" color="#FFFFFF">
	<span lang="fr-ch">Bienvenue</span> <span lang="fr-ch">votre acompte à été 
	créé avec succès !!</span></font></b><p align="center"><span lang="en-us">
<font color="#FFFFFF"><b><span lang="en-us">Welcome ! Your account has been 
	successfully created!!!</span></b></font></span><p align="center"><b>
&nbsp;</b><a href="/forum"><font color="#33CCFF"><span lang="fr-ch"><b><font color="#33CCFF">Aller 
	au Forum&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; /&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</font>Go to the forum 
!!!</font></b></span></a>

	<p align="center">
<?php 
} else {
?><b><font face="Arial" color="#FFFFFF"><span lang="fr-ch">ERREUR: impossible de 
vérifier l&#39;adresse E-mail !!!</span></font></b><p align="center">
<span lang="fr-ch"><font color="#FFFFFF"><b>ERROR : Cannot check your E-mail 
address&nbsp; !!!</b></font></span><p><b><font face="Arial" color="#FFFFFF">
<?php 
}
?>
</font></b>
<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>