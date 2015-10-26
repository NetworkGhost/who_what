<?php
// Initialize session
session_start();

$ldap_host = "";
$ldap_dn = "";
$ldap_username = "";
$ldap_password = "";
$ldap_user_group = "";
$ldap_manager_group = "";
$ldap_usr_dom = "";
$ldap_filter = "";
$user_photo_url = "";

function ldap_settings() {
	global $user_photo_url, $ldap_host, $ldap_dn, $ldap_username, $ldap_password, $ldap_filter;
	$servername = "127.0.0.1";
	$username = "who_what";
	$password = "who_what";
	$dbname = "who_what";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(ldap_host|ldap_dn|ldap_username|ldap_password|ldap_filter|site_photo_url)'";
	$result = $conn->query($sql);

	if ($result->num_rows > 1) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if ($row["configType"] == "ldap_host") {
				$ldap_host = $row["configValue"];
			}
			if ($row["configType"] == "ldap_dn") {
				$ldap_dn = $row["configValue"];
			}	
                        if ($row["configType"] == "ldap_username") {
                                $ldap_username = $row["configValue"];
                        }
                        if ($row["configType"] == "ldap_password") {
                                $ldap_password = $row["configValue"];
                        }	
                        if ($row["configType"] == "ldap_filter") {
                                $ldap_filter = $row["configValue"];
                        }
                        if ($row["configType"] == "site_photo_url") {
                                $user_photo_url = $row["configValue"];
                        }


		}
	} else {
		echo "0 results";
	}
	$conn->close();


}
 
function authenticate($user, $password) {
	global $ldap_host, $ldap_dn, $ldap_username, $ldap_password;
	ldap_settings();
	if(empty($user) || empty($password)) return false;
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
	// verify user and password
	if($bind = @ldap_bind($ldap, $user, $password)) {
	//if($bind = @ldap_bind($ldap, $user . $ldap_usr_dom, $password)) {
		//return true;
		// valid
		// check presence in groups
		$filter = "(sAMAccountName=" . $user . ")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter) or exit("Unable to search LDAP server");
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
		$access = 1;
		// check groups
		//foreach($entries[0]['memberof'] as $grps) {
			// is manager, break loop
		//	if (strpos($grps, $ldap_manager_group)) { $access = 2; break; }
			// is user
		//	if (strpos($grps, $ldap_user_group)) $access = 1;
		//}
		if ($access != 0) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true;
		} else {
			// user has no rights
			return false;
		}
	} else {
		// invalid name or password
		return false;
	}
}
?>
