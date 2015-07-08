<?php
session_start();

$username = 'user1@W.NET';
$password = 'pass1';

$px = plexcel_new(NULL, NULL);
if ($px == FALSE) {
	die(plexcel_status(NULL) . "\n");
} else {
	if (plexcel_logon($px, 'logon', $username, $password) == FALSE) {
		die(plexcel_status($px) . "\n");
	} else {
echo 'Success';
/*
		$acct = plexcel_get_account($px, NULL, PLEXCEL_SUPPLEMENTAL);
		if (is_array($acct) == FALSE) {
			die(plexcel_status($px) + "\n");
		} else {
			print_r($acct);
		}
*/
	}
}
