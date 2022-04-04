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
	
	if (isset($HTTP_GET_VARS['topic'])) {
		$topic = $HTTP_GET_VARS['topic'];
	} else if (isset($HTTP_POST_VARS['topic'])) {
		$topic = $HTTP_POST_VARS['topic'];
	}	

	check_login();
	
?><?php

	function cleanid ($topic)
	{
		return ereg_replace("(![a-fA-f0-9])", "_", $topic);

	}
	
	if (isset($user['pseudo']) && !isset($topic) &&
		isset($HTTP_POST_VARS['message']) && isset($HTTP_POST_VARS['subject'])) 
	{
		check_ip();
		
		$topic['id'] = sprintf("%08X%s", time(), md5(uniqid(rand(), true)));
		$topic['subject'] =  stripslashes($HTTP_POST_VARS['subject']);
		$topic['pseudo'] =  stripslashes($user['pseudo']);
		$topic['time'] = time();
		
		$p = "data/" . $topic['id'];
		if (!is_dir($p)) mkdir($p, 0777);
		
		dumptofile($topic, $p . "/index.php");
		
		$topic['message'] =  stripslashes($HTTP_POST_VARS['message']);
		$topic['remote_addr'] = $_SERVER['REMOTE_ADDR'];
		$topic['remote_port'] = $_SERVER['REMOTE_PORT'];
		$topic['edited'] = 0;
		$topic['in_reply_to'] = "";
		
		check_text($topic['message']);
		check_text($topic['subject']);

		dumptofile($topic, $p . "/" . $topic['id'] . ".php");

		$topic = $topic['id'];	

	} else if (isset($user['pseudo']) && isset($topic) &&
		isset($HTTP_POST_VARS['message']) && isset($HTTP_POST_VARS['subject']) &&
		isset($HTTP_POST_VARS['in_reply_to'])) 
	{
		
		check_ip();
		
		$t = cleanid($topic);
		unset($topic);
			
		$topic['id'] = sprintf("%08X%s", time(), md5(uniqid(rand(), true)));
		$topic['subject'] =  stripslashes($HTTP_POST_VARS['subject']);
		check_text($topic['subject']);
		$topic['pseudo'] =  stripslashes($user['pseudo']);
		$topic['time'] = time();
		$topic['message'] =  stripslashes($HTTP_POST_VARS['message']);
		check_text($topic['message']);
		
		$topic['remote_addr'] = $_SERVER['REMOTE_ADDR'];
		$topic['remote_port'] = $_SERVER['REMOTE_PORT'];
		$topic['edited'] = 0;
		$topic['in_reply_to'] = stripslashes($HTTP_POST_VARS['in_reply_to']);
		
		dumptofile($topic, "data/" . $t . "/" . $topic['id'] . ".php");
	
		$topic = $t;
	}	
	
	if (!isset($topic)) exit(0);
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
			<p><font size="4" color="#FFFFFF"><b><?php echo $lang['topic_index']; ?></b></font></td>
	</tr>
</table>
<p align="center"><a href="index.php?page=<?php echo $page; ?>">
<img border="0" src="../fr.gif" width="30" height="15"></a>&nbsp;&nbsp;&nbsp;
<a href="index.php?lang=en&page=<?php echo $page; ?>">
<img border="0" src="../en.gif" width="30" height="15"></a></p>


<?php
	function quotereply ($text)
	{
		$text = htmlspecialchars($text);
		$text = ereg_replace("(^|\n)", "> ", $text);
		$text .= "\n";	   	
		return $text;
	}


if (isset($user['pseudo']) && isset($topic) && 
	isset($HTTP_POST_VARS['in_reply_to']) && 
	!isset($HTTP_POST_VARS['message'])) 
{
	$t = cleanid($topic);
	$r = cleanid($HTTP_POST_VARS['in_reply_to']);
	
	include "data/" . $t . "/" . $r . ".php";
	
?> 
<center>
<form action="topic.php?page=1000#end" method="post">
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />

<table border="1" width="580" cellspacing="0">
	<tr>
		<td>
		<font face="Arial" color="#FFFFFF"><b><?php echo $lang['reply']; ?></b></font><br/>
		<font face="Arial" color="#FFFFFF"><?php echo $lang['subject']; ?></font> : 
		<input name="subject" type="text" size="67"
 value="Re: <?php echo htmlspecialchars($var['subject']); ?>"/><br/>
<textarea name="message" ROWS="10" COLS="80" WARP="HARD" >
<?php
	echo "[ " . htmlspecialchars($var['pseudo']) . " ] :\n";
	echo quotereply($var['message']); 
?></textarea>
<center>
<input type="hidden" name="in_reply_to" value="<?php echo $HTTP_POST_VARS['in_reply_to']; ?>" />
<input type="hidden" name="topic" value="<?php echo $topic; ?>" />
<input type="submit" value="<?php echo $lang['send'] ?>" />
</center>
</td>
	</tr>
</table>

</form>
</center> 
<p>&nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=<?php echo $lang['lang']; ?>">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>
<?php
	exit(0);
}
?> 



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
<form action="topic.php" method="post">
<input type="hidden" name="topic" value="<?php echo $topic; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
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

<center><a href="."></href="."><font face="Arial" color="#33CCFF" size="5">
<?php echo $lang['back_to_index']; ?></font></a></center>

<center>&nbsp;<table border="1" width="640" cellspacing="0">
<?php

	function hyperlink($text)
	{
		$text = htmlspecialchars($text);
		$text = ereg_replace("\n", "<br/>\n", $text);
	   	// match protocol://address/path/
	   	$text = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_&/?=#;%-])*", "<a target=\"_blank\" href=\"\\0\"><font color=\"#33CCFF\">\\0</font></a>", $text);
		return $text;
	}

	function getdir($dir)
	{
		$dh  = opendir($dir);
		$files = array();
		while (false !== ($filename = readdir($dh))) {
			if ($filename != "." && $filename != ".." && $filename != "index.php") {
	   			$files[] = $filename;
	   		}
		}
		sort($files);
		return $files;
	}
	
	$p = "data/" . $topic;
	$arr = getdir($p);	
	$len = count($arr);
		
	$nb_page = (int)($len / 10);
	if ($len % 10 > 0) $nb_page++;
	if ($page > $nb_page) $page = $nb_page;
	if ($page < 1) $page = 1;
	$start = (10 * ($page - 1));
	$end = (10 * ($page)) - 1;
	if ($start >= $len) $start = $len - 1;
	if ($end >= $len) $end = $len - 1;
	
	$vals = array_values($arr);
	for ($i = $start; $i <= $end; $i++) {
		$d = $p . "/" . $vals[$i];
		unset ($var);
		include $d;
?>
	<tr>
		<td valign="top"><font face="Arial" color="#FFFFF"><b>

<?php
		
		echo htmlspecialchars($var['subject']);
?>
		</b>&nbsp;<br/>
<?php
		
		echo hyperlink($var['message']);
?>
		
		</font></td>
		<td width="111"><center><font face="Arial" color="#FFFFFF">

<?php
		echo htmlspecialchars($var['pseudo']);
		echo "<br/>";
		echo date("H\hi d.m.Y", $var['time']);

?>		
		</font>
<?php
if (isset($user['pseudo'])) {
?> 

		<form action="topic.php" method="post">
		<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
		<input type="hidden" name="in_reply_to" value="<?php echo $var['id']; ?>" />
		<input type="hidden" name="topic" value="<?php echo $topic; ?>" />
		<input type="submit" value="<?php echo $lang['reply'] ?>" />
		</form>
<?php
}
?> 

		</center></td>
	</tr>
<?php
		unset($var);
	}
?>

</table>
</center>

<?php
if (!isset($user['pseudo'])) {
?> 
<center>
<form action="topic.php" method="post">
<input type="hidden" name="topic" value="<?php echo $topic; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang['lang']; ?>" />
<input type="hidden" name="pseudo" value="" />
<input type="hidden" name="passwd" value="" />
<input type="submit" value="<?php echo $lang['reply_to_message']; ?>" />
</form>
</center>
<?php
} 
?> 


<center>

<?php

	if ($page > 1) {
			echo '<a href="topic.php?lang=' . $lang['lang'] . '&page=' . ($page - 1);
			echo '&topic=' . $topic;
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
			echo '<a href="topic.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '&topic=' . $topic;
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
			echo '<a href="topic.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '&topic=' . $topic;
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
			echo '<a href="topic.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '&topic=' . $topic;
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
			echo '<a href="topic.php?lang=' . $lang['lang'] . '&page=' . $pn;
			echo '&topic=' . $topic;
			echo '" ><font face="Arial" color="#33CCFF">' . $b . $pn . $_b . "</font></a>&nbsp;\n";
		}

	}
	
	if ($page < $nb_page) {
			echo '&nbsp; &nbsp; <a href="topic.php?lang=' . $lang['lang'] . '&page=' . ($page + 1);
			echo '&topic=' . $topic;
			echo '" ><font face="Arial" color="#33CCFF">' . $lang['next'] . "</font></a>\n";
	}

?>
</center>

<center><a href="."></href="."><font face="Arial" color="#33CCFF" size="5">
<?php echo $lang['back_to_index']; ?></font></a></center>

<p><a name="end"/> &nbsp;<p><b><font face="Arial" color="#FFFFFF">
Contact :</font>
	<a href="/send.php?lang=<?php echo $lang['lang']; ?>">
	<font color="#33CCFF">Jean-Marc Lienher</font></a></b>
</body>

</html>
