<?php
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
error_reporting(E_ALL & ~E_NOTICE);
include "../includes/config.php";
include "../includes/functions.php";
$mlink = mysql_connect($dbserver,$dbusername,$dbpassword) or die("Kan ikke koble til ".$dbserver.".");
mysql_select_db($database) or die("Kan ikke velge databasen ".$database.".");
session_start();
$username = strtolower(mysql_real_escape_string(stripslashes($_POST["username"])));
$password = md5(mysql_real_escape_string(stripslashes($_POST["password"])));
if(!empty($username) && !empty($password)){
	$result = mysql_query("SELECT * FROM users WHERE username='$username'");
	$row = mysql_fetch_array($result);
	$salt = $row["salt"];
	$salt2 = $codedsalt.$salt;
	$password = crypt($password, '$6$rounds=5000$'.$salt2.'$');
	$ip = getenv('REMOTE_ADDR');
	$result = mysql_query("SELECT * FROM users WHERE username='$username' and password='$password'");
	$count=mysql_num_rows($result);
	if($count==1){
		mysql_query("UPDATE users SET forgottoken='' WHERE username = '$username'");
		$_SESSION['username'] = $username;
		$_SESSION['ip'] = $ip;
		header("Location: ../index.php");
	}else{
		header("Location: wrong.php");
	};
}else{
	include 'login_header.php';
	print '<form name="Registrering" action="'.$_SERVER["PHP_SELF"].'" method="post">
<font size="2"><strong>Brukernavn:</strong><br />
<input type="text" name="username" size="30" /><br />
<strong>Passord</strong><br />
<input type="password" name="password" size="30" /><br />
<br />
<input type="submit" value="Logg inn" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
	include 'login_footer.php';
};
if (!empty($mlink)) {
	mysql_close($mlink);
};
?>
