<?php
// Initialize session
session_start();
//Variables below are for connecting to the ldap server
//and other site wide settings that are used.
//Global variable for ldap host
$ldap_host = "";
//Global variable for ldap dn string
$ldap_dn = "";
//Global variable for ldap username
$ldap_username = "";
//Global variable for ldap user password
$ldap_password = "";
//Global variable for ldap filter
$ldap_filter = "";
//Global variable for ldap user group
$ldap_user_group = "";
//Global variable for ldap manager group
$ldap_manager_group = "";
//$ldap_usr_dom = "";
//Global variable for ldap filter
$ldap_filter = "";
//Global variable for user photo URL
$user_photo_url = "";
//Site Admin Username
$site_admin_username = "admin";
$site_admin_password = "admin123";
//++++++++++++++++++++++++++++++++++++++++++++++++
// Retrieves ldap and site configuration entries from the application database. 
// Global variables are set for use in other functions
//++++++++++++++++++++++++++++++++++++++++++++++++
function ldap_settings() {
	global $user_photo_url, $ldap_host, $ldap_dn, $ldap_username, $ldap_password, $ldap_filter, $site_admin_username, $site_admin_password;
	//Database information
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

	//Query all site configuration info
	$sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(site_admin|ldap_host|ldap_dn|ldap_username|ldap_password|ldap_filter|site_photo_url)'";
	$result = $conn->query($sql);
	//Set global variables
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
                        if ($row["configType"] == "site_admin_username" && !empty($row["configValue"])) {
                                $site_admin_username = $row["configValue"];
                        }
                        if ($row["configType"] == "site_admin_password" && !empty($row["configValue"])) {
                                $site_admin_password = $row["configValue"];
                        }


		}
	} else {
		echo "0 results";
	}
	$conn->close();


}
//++++++++++++++++++++++++++++++++++++++++++++++++
// Authenticates user and sets PHP session variables user and access. 
// user: The user to be authenticated
// password: The users password
//++++++++++++++++++++++++++++++++++++++++++++++++
function authenticate($user, $password) {
	global $ldap_host, $ldap_dn, $ldap_username, $ldap_password, $site_admin_username,$site_admin_password;
	ldap_settings();
	$ldap_user = 'cn='.$user.','.$ldap_dn;
	if ($user === $site_admin_username) {
		if ($password === $site_admin_password) {
			$access = 2;		
			$_SESSION['user'] = $user;
                        $_SESSION['access'] = $access;	
		} else {
			return false;
		}
		return true;
	}
	if(empty($user) || empty($password)) return false;
	// connect to active directory
	//UNCOMMENT the line below for troubleshooting LDAP issues
	#ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
	$ldap = ldap_connect('ldaps://'.$ldap_host);
	// verify user and password
	if($bind = ldap_bind($ldap, $ldap_user, $password)) {
		//set filter for search to be equal to the users account name
		$filter = "(sAMAccountName=" . $user . ")";
		//Attribute value to be returned. Not in use but keeping in place for the future. 
		$attr = array("memberof");
		//execute search for user based on filter
		$result = ldap_search($ldap, $ldap_dn, $filter) or exit("Unable to search LDAP server");
		//If entries are returned, user is found
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
		$access = 1;
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
