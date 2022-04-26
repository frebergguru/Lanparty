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
if(isset($_SESSION['username'])){
	session_destroy();
	header("Location: ?check=1");
};
if($_GET["check"] == 1){
	include 'logout_header.php';
	if(isset($_SESSION['username'])){
		print 'Det skjedde en feil ved ut logging, vennligst kontakt admin';
	}else{
		print 'Du er n&aring; logget ut!=)';
	};
	include 'logout_footer.php';
}else{
	include 'logout_header.php';
	print 'Det ser ut som at du allerede er logget ut?<br />
<br />
<a href="../login">Du kan trykke her for &aring; logge inn.</a>';
	include 'logout_footer.php';
};
if (!empty($mlink)) {
	mysql_close($mlink);
};
?>
