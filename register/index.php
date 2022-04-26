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
error_reporting(E_ALL & ~E_NOTICE);
include "../includes/config.php";
include "../includes/functions.php";
$mlink = mysql_connect($dbserver,$dbusername,$dbpassword) or die("Kan ikke koble til ".$dbserver.".");
mysql_select_db($database) or die("Kan ikke velge databasen ".$database.".");
$fullname = mysql_real_escape_string(stripslashes($_POST["fullname"]));
$username = strtolower(mysql_real_escape_string(stripslashes($_POST["username"])));
$randomsalt=random_salt();
$salt2 = $codedsalt.$randomsalt;
$password = mysql_real_escape_string(stripslashes(md5($_POST["password"])));
$password2 = crypt($password, '$6$rounds=5000$'.$salt2.'$');
$email = mysql_real_escape_string(stripslashes($_POST["email"]));
if(!empty($fullname) && !empty($username) && !empty($password2) && !empty($email)){
	$result = mysql_query("SELECT * FROM users WHERE username='$username'");
	if(mysql_num_rows($result)!=0){
		include "register_header.php";
		print '<strong>Brukernavnet finnes fra f&oslash;r, vennligst velg et annet!<br />
<form name="Registrering" action="'.$_SERVER["PHP_SELF"].'" method="post">
<font size="2"><strong>Fult navn:</strong><br />
<input type="text" name="fullname" size="30" /><br />
<strong>Brukernavn:</strong><br />
<input type="text" name="username" size="30" /><br />
<strong>Passord:</strong><br />
<input type="password" name="password" size="30" /><br />
<strong>E-post:</strong><br />
<input type="text" name="email" size="30" /><br />
<br />
<input type="submit" value="Registrer" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
		include "register_footer.php";
		exit;
	};
	$result = mysql_query("SELECT * FROM users WHERE email='$email'");
	if(mysql_num_rows($result)!=0){
	include "register_header.php";
	print '<strong>E-post adressen finnes fra f&oslash;r, vennligst velg pr&oslash;v en annen!<br />
<form name="Registrering" action="'.$_SERVER["PHP_SELF"].'" method="post">
<font size="2"><strong>Fult navn:</strong><br />
<input type="text" name="fullname" size="30" /><br />
<strong>Brukernavn:</strong><br />
<input type="text" name="username" size="30" /><br />
<strong>Passord:</strong><br />
<input type="password" name="password" size="30" /><br />
<strong>E-post:</strong><br />
<input type="text" name="email" size="30" /><br />
<br />
<input type="submit" value="Registrer" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
	include "register_footer.php";
	exit;
	};
	mysql_query("INSERT INTO users (fullname, username, password, salt, email) VALUES ('$fullname', '$username', '$password2', '$randomsalt', '$email')");
	header("Location: ../login");
	exit;
}else{
	include "register_header.php";
	print '<form name="Registrering" action="'.$_SERVER["PHP_SELF"].'" method="post">
<font size="2"><strong>Fult navn:</strong><br />
<input type="text" name="fullname" size="30" /><br />
<strong>Brukernavn:</strong><br />
<input type="text" name="username" size="30" /><br />
<strong>Passord:</strong><br />
<input type="password" name="password" size="30" /><br />
<strong>E-post:</strong><br />
<input type="text" name="email" size="30" /><br />
<br />
<input type="submit" value="Registrer" /> || <input type="reset" value="Nullstill" />
</font>
</form>';
	include "register_footer.php";
};
if (!empty($mlink)) {
	mysql_close($mlink);
};
?>
