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
include 'forgotpassword_header.php';
print '<form name="forgotpassword" action="index.php" method="post">
	<strong>Brukernavn: <br /></strong>
	<input type="text" name="username" size="30" /><br />
	<strong>Nullstillings kode: (Sjekk mailen din)</strong><br />
	<input type="text" name="forgottoken" size="30" /><br />
	<br />
	<input type="submit" value="OK" /> || <input type="reset" value="Nullstill" />
	</font>
	</form>';
include 'forgotpassword_footer.php';
?>
