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

	if (isset($HTTP_GET_VARS['page'])) {
		$page = $HTTP_GET_VARS['page'];
	} else if (isset($HTTP_POST_VARS['page'])) {
		$page = $HTTP_POST_VARS['page'];
	} else {
		$page = "1";
	}	
	check_login();
	
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
			<p><font size="4" color="#FFFFFF"><b><?php echo $lang['forum_index']; ?></b></font></td>
	</tr>
</table>
<p align="center"><a href="index.php?page=<?php echo $page; ?>">
<img border="0" src="../fr.gif" width="30" height="15"></a>&nbsp;&nbsp;&nbsp;
<a href="index.php?lang=en&page=<?php echo $page; ?>">
<img border="0" src="../en.gif" width="30" height="15"></a></p>
<p align="right">
<?php
if (isset($user['pseudo'])) {
?> 
<form action="index.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
<input type="hidden" name="logout" value="1" />
<input type="submit" value="<?php echo $lang['logout'] . ' '. $user['pseudo']; ?>" />
</form>
<?php
} else {
?> 
<form action="index.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
<font color="#FFFFFF"><?php echo $lang['pseudo']; ?></font>
<input type="text" name="pseudo" size="12" />
<font color="#FFFFFF"><?php echo $lang['passwd']; ?></font>
<input type="password" name="passwd" size="12" />
<input type="submit" value="<?php echo $lang['send']; ?>" />
</form>
<?php
} 
?> 
</p>
<center>&nbsp;<table border="1" width="640" cellspacing="0">
<?php

	function getdir($dir)
	{
		$dh  = opendir($dir);
		$files = array();
		while (false !== ($filename = readdir($dh))) {
			if ($filename != "." && $filename != ".." && $filename != "index.php") {
				$j = "f" . filemtime($dir . "/" . $filename);
	   			$files[$j] = $filename;
	   		}
		}
		krsort($files);
		return $files;
	}
	
	$p = "data";
	if (!is_dir($p)) mkdir($p, 0777);

	$arr = getdir("data");	
	$len = count($arr);

	$rat = 10;	
	
	$nb_page = (int)($len / $rat);
	if ($len % $rat > 0) $nb_page++;
	if ($page > $nb_page) $page = $nb_page;
	if ($page < 1) $page = 1;

	$start = ($rat * ($page - 1));
	$end = ($rat * ($page)) - 1;
	if ($start >= $len) $start = $len - 1;
	if ($end >= $len) $end = $len - 1;
	if ($len < 1) {
		$start = 1;
		$end = 0;
	}
	
	$vals = array_values($arr);
	for ($i = $start; $i <= $end; $i++) {
		$d = "data/" . $vals[$i];
		$a = getdir($d);
		$b = array_values($a);
		unset ($var);
		include $d . "/index.php";
		$ind = $var;
		unset ($var);
		include $d . "/" . $b[0];
?>
	<tr>
		<td><a href="topic.php?page=1000&lang=<?php echo $lang["lang"]; ?>&topic=<?php echo $vals[$i]; ?>#end"><font face="Arial" color="#33CCFF">

<?php
		
		echo htmlspecialchars(substr($ind['subject'], 0, 80));
?>
		&nbsp;</font></a></td>
		<td width="111"><center><font face="Arial" color="#FFFFFF">

<?php
		echo htmlspecialchars($var['pseudo']);
		echo "<br/>(" . count($b) . ") ";
		echo date("H\hi d.m.Y", $var['time']);

?>		
		&nbsp;</font></center></td>
	</tr>
<?php
		unset($var);
	}
?>

</table>
</center>

<center>
<?php

	if ($page > 1) {
			echo '<a href="index.php?lang=' . $lang['lang'] . '&page=' . ($page - 1);
			echo '" ><font face="Arial" color="#33CCFF">' . $lang['previous'] . "</font></a> &nbsp; &nbsp; \n";
	}
	if ($nb_page < 11) {
		for ($pn = 1; $pn <= $nb_page; $pn++) {
			$b = '';
			$_b = '';
			if ($pn == $page) {
				$b = '<font size="5" color="#FFFFFF"><b>';
				$_b = '</b></font>';
			}	
			echo '<a href="index.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '" ><font face="Arial" color="#33CCFF">' . $b . $pn . $_b . "</font></a>&nbsp;\n";
		}
	} else {
		for ($pn = 1; $pn <= 3; $pn++) {
			$b = '';
			$_b = '';
			if ($pn == $page) {
				$b = '<font size="5" color="#FFFFFF"><b>';
				$_b = '</b></font>';
			}	
			echo '<a href="index.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '" ><font face="Arial" color="#33CCFF">' . $b . $pn . $_b . "</font></a>&nbsp;\n";
		}
		
		echo ' <font size="5" color="#FFFFFF">...</font> ';
			
		$pp = $page - 2;
		if ($pp <= 3) $pp = 4;
		if ($pp >= $nb_page - 6) $pp = $nb_page - 7;
		$pe = $pp + 4;
		for ($pn = $pp; $pn <= $pe; $pn++) {
			$b = '';
			$_b = '';
			if ($pn == $page) {
				$b = '<font size="5" color="#FFFFFF"><b>';
				$_b = '</b></font>';
			}	
			echo '<a href="index.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '" ><font face="Arial" color="#33CCFF">' . $b . $pn . $_b . "</font></a>&nbsp;\n";
		}
		
		echo ' <font size="5" color="#FFFFFF">...</font> ';
		
		for ($pn = $nb_page - 2; $pn <= $nb_page; $pn++) {
			$b = '';
			$_b = '';
			if ($pn == $page) {
				$b = '<font size="5" color="#FFFFFF"><b>';
				$_b = '</b></font>';
			}	
			echo '<a href="index.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '" ><font face="Arial" color="#33CCFF">' . $b . $pn . $_b . "</font></a>&nbsp;\n";
		}

	}
	
	if ($page < $nb_page) {
			echo '&nbsp; &nbsp; <a href="index.php?lang=' . $lang['lang'] . '&page=' . ($page + 1);
			echo '" ><font face="Arial" color="#33CCFF">' . $lang['next'] . "</font></a>\n";
	}

?>
</center>
<?php
if (isset($user['pseudo'])) {
?>
<center>
<form action="topic.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />

<table border="1" width="580" cellspacing="0">
	<tr>
		<td>
		<font face="Arial" color="#FFFFFF"><b><?php echo $lang['new_subject']; ?></b></font><br/>
		<font face="Arial" color="#FFFFFF"><?php echo $lang['subject']; ?></font> : 
		<input name="subject" type="text" size="67"
/><br/><textarea name="message" ROWS="10" COLS="80" WARP="HARD" >
</textarea>
<center>
<input type="submit" value="<?php echo $lang['send'] ?>" />
</center>
</td>
	</tr>
</table>

</form>
</center> 
<?php
} else {
?>
<center> 
<form action="index.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
<input type="hidden" name="pseudo" value="" />
<input type="hidden" name="passwd" value="" />
<input type="submit" value="<?php echo $lang['send_new_topic']; ?>" />
</form>
</center>
<?php
}
?> 

<p>&nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=<?php echo $lang['lang']; ?>">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>