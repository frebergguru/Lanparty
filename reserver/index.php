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
if(isset($_SESSION['username']) && !empty($_GET['id'])){
	$reserver = (int) $_GET['id'];
	$result = mysql_query("SELECT * FROM `config`") or mysqldie("Kan ikke lese fra tabellen \"config\"");
	$row = mysql_fetch_array($result);
	$maxseats = $row["maxseats"];
	if($reserver < $maxseats){
		$result = mysql_query("SELECT * FROM users WHERE rseat='$reserver'");
		if(mysql_num_rows($result)==0){
			$result = mysql_query("SELECT * FROM users WHERE username='".$_SESSION['username']."'");
			$row = mysql_fetch_array($result);
			$rseat = $row["rseat"];
			if (empty($rseat) OR $rseat == 0){
				$userid = $row["id"];
				mysql_query("UPDATE users SET rseat='$reserver' WHERE username='".$_SESSION['username']."'");
				mysql_query("INSERT INTO reservations (taken, user_id) VALUES($reserver, $userid)");
			};
		};
	}else{
		include 'reserver_header.php';
		print '<strong>Sete du har pr&oslash;vd &aring; registrere finnes ikke!!</strong>';
		include 'reserver_footer.php';
		exit;
	};
	Header("Location: ../");
}else{
	Header("Location: ../");
};
if (!empty($mlink)) {
	mysql_close($mlink);
};
?>
