<?
/*
* Lanparty seat reservation script
* Copyright (C) 2005 - 2010 Morten Freberg - LastNetwork.Net
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
* */
include "../includes/config.php";
include "../includes/functions.php";
session_start();
ini_set('session.gc_maxlifetime', 60*30);
$sname = $_SERVER["SCRIPT_NAME"];
$fname = GetFileName($_SERVER["PHP_SELF"]);
$webpath = str_replace($fname,"",$sname);
$sqllink = mysql_connect($dbserver,$dbusername,$dbpassword) or die("Kan ikke koble til ".$dbserver.".");
mysql_select_db($database) or die("Kan ikke velge databasen ".$database.".");
$fullname = mysql_real_escape_string(stripslashes($_POST["fullname"]));
$forgottoken = mysql_real_escape_string(stripslashes($_POST["forgottoken"]));
$username = strtolower(mysql_real_escape_string(stripslashes($_POST["username"])));
$sm = $_GET["sm"];
$randomsalt=random_salt();
$salt2 = $codedsalt.$randomsalt;
$randomsalt=random_salt();
$salt2 = $codedsalt.$randomsalt;
$password = mysql_real_escape_string(stripslashes(md5($_POST["password"])));
$password2 = mysql_real_escape_string(stripslashes(md5($_POST["password2"])));
$password3 = crypt($password, '$6$rounds=5000$'.$salt2.'$');
$email = mysql_real_escape_string(stripslashes($_POST["email"]));

if($sm == 1 && !empty($username) && !empty($email) && empty($_SESSION["forgotpassword"])){
	$result = mysql_query("SELECT * FROM users WHERE username='$username' AND email='$email'");
	if(mysql_num_rows($result)!=0){
		$_SESSION["forgotpassword"] = 1;
		$randomsalt2 = random_salt();
		mysql_query("UPDATE users SET forgottoken='$randomsalt2' WHERE email = '$email'");
		$message = "Du må gå inn på lenka under og skrive inn følgende nullstillings kode: ".$randomsalt2."\n for å bytte passordet ditt!\n\nhttp://".$_SERVER['SERVER_NAME'].$webpath."resetform.php";
		mail($email,'Lanparty - glemt passord',$message, "From: hypnotize@lastnetwork.net");
		header("Location: resetform.php");
		exit;
	}else{
		include 'forgotpassword_header.php';
		print 'Brukernavnet eller e-mail adressen stemmer ikke!';
		include 'forgotpassword_footer.php';
		exit;
	};
};

if(!empty($forgottoken) && !empty($username) && !empty($email) && !empty($password) && !empty($password2)){
	$result = mysql_query("SELECT * FROM users WHERE forgottoken='$forgottoken' AND username='$username' AND email='$email'");
	if(mysql_num_rows($result)!=0){
	if($password == $password2){
mysql_query("UPDATE users SET password='$password3', salt='$randomsalt', forgottoken='' WHERE email='$email'");
echo mysql_error($sqllink);
include "forgotpassword_header.php";
print 'Passordet ditt er n&aring; endret.=)<br />
	<a href="../login/">Du kan trykke her for &aring; logge inn.</a>';
include "forgotpassword_footer.php";
	}else{
		include 'forgotpassword_header.php';
		print 'Passordene var ikke like, vennligst pr&oslash;v igjen!';
		include 'forgotpassword_footer.php';
	};
	}else{
		include 'forgotpassword_header.php';
		print 'E-mail adressen eller nullstillings koden stemmer ikke!';
		include 'forgotpassword_footer.php';
	};
	exit;
};

if(!empty($forgottoken) && !empty($username)){
$result = mysql_query("SELECT * FROM users WHERE forgottoken='$forgottoken' AND username='$username'");
if(mysql_num_rows($result)!=0){
	include "forgotpassword_header.php";
	print '<strong><form name="forgotpassword" action="'.$_SERVER["PHP_SELF"].'" method="post">
<font size="2"><strong>Skriv inn nytt passord:</strong><br />
<input type="password" name="password" size="30" /><br />
<strong>Skriv inn passordet en gang til:</strong><br />
<input type="password" name="password2" size="30" /><br />
<strong>E-post:</strong><br />
<input type="text" name="email" size="30" /><br />
<input type="hidden" name="username" value="'.$username.'" />
<input type="hidden" name="forgottoken" value="'.$forgottoken.'" />
<br />
<input type="submit" value="Endre passord" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
	include "forgotpassword_footer.php";
	exit;
};
}else{
	include "forgotpassword_header.php";
	if(!empty($_SESSION["forgotpassword"])){
		print '<strong>Du har alt spurt etter et passord.. (Sjekk e-mailen din!)</strong><br /><hr align="center" />';
	};
	print '<form name="Registrering" action="'.$_SERVER["PHP_SELF"].'?sm=1" method="post">
<strong>Brukernavn:</strong><br />
<input type="text" name="username" size="30" /><br />
<strong>E-post:</strong><br />
<input type="text" name="email" size="30" /><br />
<br />
<input type="submit" value="Send nytt passord" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
	include "forgotpassword_footer.php";
};
?>
