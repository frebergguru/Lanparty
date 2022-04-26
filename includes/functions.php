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
function random_salt() {
	$length = 16;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$real_string_legnth = strlen($characters) - 1;

	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, $real_string_legnth)];
	}
	return $string;
}

//SCRIPT PROVIDED BY WEBUNE.COM
function GetFileName($php_self){
	$filename = explode("/", $php_self); // THIS WILL BREAK DOWN THE PATH INTO AN ARRAY
	$filename = array_reverse($filename ); // THIS WILL MAKE THE LAST ELEMENT THE FIRST
	return $filename[0];
} 
