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
?><?php
	
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
		<a href="http://www.oksidizer.com/forum/index.php?lang=<?php echo $lang['lang']; ?>"><font color="#33CCFF" face="arial">Forum</font></a>
		<font color="#FFFFFF" face="arial"> / </font>
	</td>
	</tr><tr>


		<td align="center"><img alt="oKsidiZer" src="../logo.gif" width="491" height="94" /><p>
			<font size="5" color="#C0C0C0"><b>Forum</b></font></p>
			<p><font size="4" color="#FFFFFF"><b><?php echo $lang['login_or_new']; ?></b></font></td>
	</tr>
</table>
<p align="center">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php if (isset($HTTP_POST_VARS['topic'])) { ?>
<input type="hidden" name="topic" value="<?php echo $HTTP_POST_VARS['topic']; ?>" />
<input type="hidden" name="page" value="<?php echo $HTTP_POST_VARS['page']; ?>" />
<?php } ?>
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
<font color="#FFFFFF"><?php echo $lang['pseudo']; ?></font>
<input type="text" name="pseudo" size="12" />
<font color="#FFFFFF"><?php echo $lang['passwd']; ?></font>
<input type="password" name="passwd" size="12" />
<input type="submit" value="<?php echo $lang['send']; ?>" />
</form>
</p>
<center>
<form action="newuser.php?lang=<?php echo $lang['lang']; ?>" method="post">
<input type="submit" value="<?php echo $lang['create_new']; ?>" />
</form>
</center>





<p>&nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>
