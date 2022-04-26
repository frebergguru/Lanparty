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
session_start();      
include "includes/config.php";
function mysqldie($message) {
        //Get the MySQL error message
        $mysqlerror = mysql_error();
        //If the MySQL link is active then
        if (!empty($mlink)) {
                //close the connection
                mysql_close($mlink);
        }
        //die with a message and the MySQL error
        die($message.' - '.$mysqlerror);
};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="no">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seter</title>
</head>
<body style="background-color: #CCCCCC">
<div align="center">
<table bgcolor="#DEDEDE" cellpadding="20" cellspacing="0" style="border: 1px solid #000000">
<tr>
<td>
<div align="center"><strong>Symbol forklaring:</strong></div><br />
<img src="./img/gul.jpg" border="0" height="15" alt="Ledig sete" /> Valgt sete - <img src="./img/roed.jpg" border="0" height="15" alt="Opptatt sete" /> Opptatt sete <img src="./img/groen.jpg" border="0" height="15" alt="Ledig sete" /> -  Ledig sete
</td>
</tr>
</table>
<br />
<table bgcolor="#DEDEDE" cellpadding="20" cellspacing="0" style="border: 1px solid #000000">
<tr bgcolor="#DFDFDF">
<td><div align="center"><strong>[ - FRONT - ]</strong></div>
<hr align="center" />
<?php
$mlink = mysql_connect($dbserver, $dbusername, $dbpassword) or mysqldie("Kan ikke koble til ".$dbserver.".");
mysql_select_db($database) or mysqldie("Kunne ikke velge databasen".$database.".");
function seats($maxseats, $seat_width, $seat_height, $width) {
	$i = 0;
	while ($i < $maxseats) {
		$i++;
		$result = mysql_query("SELECT * FROM `reservations` WHERE taken = '$i'") or mysqldie("Kan ikke lese fra tabellen \"reservations\"");
		$row = mysql_fetch_array($result);
		if($row["taken"] == $i) {
			print '<a href="?reserver='.$i.'"><img src="./img/roed.jpg" border="0" width="'.$seat_width.'" height="'.$seat_height.'" alt=" '.$i.' - Opptatt sete" /></a> ';
		}elseif($_GET["reserver"] == $i){
			print '<img src="./img/gul.jpg" border="0" width="'.$seat_width.'" height="'.$seat_height.'" alt=" '.$i.' - Valgt sete" /> ';
		} else {
			print '<a href="?reserver='.$i.'"><img src="./img/groen.jpg" border="0" width="'.$seat_width.'" height="'.$seat_height.'" alt=" '.$i.' - Ledig sete" /></a> ';
		};
		if(!isset($width2)){$width2='';};
	$width2 = $width2 + 1;
	if ($width2 == $width) {
		$width2 = 0;
		print '<br />
			';
		};
	};
};
$result = mysql_query("SELECT * FROM `config`") or mysqldie("Kan ikke lese fra tabellen \"config\"");
$row = mysql_fetch_array($result);
$maxseats = $row["maxseats"];
$seat_width = $row["seat_width"];
$seat_height = $row["seat_height"];
$width = $row["width"];
seats($maxseats,$seat_width,$seat_height,$width);
?>
</td>
</tr>
</table>
<br />
<?php
$result = mysql_query("SELECT * FROM `reservations` WHERE taken = '$_GET[reserver]'") or mysqldie("Kan ikke lese fra tabellen \"reservations\"");
while ($row = mysql_fetch_array($result)) {
	if ($row["taken"] == $_GET["reserver"]) {
	$opptatt = 1;
	$user = $row["user_id"];
	};
};
if(!isset($opptatt)){$opptatt='';};
if ($opptatt == "1") {
$result = mysql_query("SELECT * FROM `users` WHERE id = '$user'") or mysqldie("Kan ikke lese fra tabellen \"reservations\"");
$row = mysql_fetch_array($result);
$username = $row["username"];
};
if(!empty($_GET["reserver"])) {
		print '<table bgcolor="#DEDEDE" cellpadding="20" cellspacing="0" style="border: 1px solid #000000"><tr>';
		if (!empty($opptatt)) {
			print '<td><strong>Sete nummer '.$_GET["reserver"].' er reservert av '.$username.'!</strong></td>';
		} else {
			if(!isset($_SESSION['username'])){
				print '<td><strong>Du m&aring; logge inn f&oslash;r du kan registrere et sete!</strong><br />
<a href="./login">Logg inn</a> || <a href="./register">Registrer ny bruker</a> || <a href="forgotpassword">Glemt passord</a></td>';
			}else{
				$result = mysql_query("SELECT * FROM users WHERE username='".$_SESSION['username']."'");
				$row = mysql_fetch_array($result);
				$rseat = $row["rseat"];
				if (empty($rseat) OR $rseat == 0){
				print '<td><strong>Vil du reservere sete nummer '.$_GET["reserver"].' ?</strong> <a href="reserver/?id='.$_GET["reserver"].'">Ja</a></td>';
			}else{
				print '<td><strong>Du har alt reservert sete nummer: '.$rseat.'!</strong></td>';
			};
		};
	};
print '</tr></table>';
};
?>
<br />
<table bgcolor="#DEDEDE" cellpadding="20" cellspacing="0" style="border: 1px solid #000000">
<tr>
<?
if(isset($_SESSION['username'])){
	print '<td><font size="2"><strong><a href="./logout">Trykk her for &aring; logge ut!</a>
<hr align="center">';
};
if(!isset($_SESSION['username'])){
	print '<td><font size="2"><strong><a href="login">Logg inn</a> || <a href="register">Registrer</a> || <a href="forgotpassword">Glemt passord</a></strong></font>
		<hr align="center">';
};
print '<font size="2"><strong>Copyright 2005 - 2010 Morten Freberg - LastNetwork.Net</strong></font></td>';
if (!empty($mlink)) {
	mysql_close($mlink);
};
?>
</tr>
</table>
</div>
</body>
</html>
