<?php
session_start();
if (isset($_SESSION['user']) && (!$_SESSION['user'] === "admin" || strpos($_SESSION['user'],"joe") < 0)) {
        header("Location: index.php");
        die();


}
?>

<?php

$site_title = "";
$site_admin_username = "";
$ldap_host = "";
$ldap_dn = "";
if (isset($_POST['siteTitle'])) {
	$site_title = $_POST['siteTitle'];
}; 
if (isset($_POST['siteAdminUsername'])) {
        $site_admin_username = $_POST['siteAdminUsername'];
};
if (isset($_POST['ldapServer'])) {
        $ldap_host = $_POST['ldapServer'];
};
if (isset($_POST['ldapDN'])) {
        $ldap_dn = $_POST['ldapDN'];
};

function update_site_admin() {
        global $site_title, $site_admin_username;
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

        $sql = "UPDATE tbl_configuration SET configValue = '".$site_title."' WHERE configType = 'site_title'";
        $result = $conn->query($sql);
        if ($result === TRUE) {
		echo "Update Successful";
        } else {
		echo "Update Failed";
	}
	$sql = "UPDATE tbl_configuration SET configValue = '".$site_admin_username."' WHERE configType = 'site_admin_username'";
	$result = $conn->query($sql);
        if ($result === TRUE) {
                echo "Update Successful";
        } else {
                echo "Update Failed";
        }
	
        $conn->close();


}
function update_ldap_admin() {
        global $site_title, $site_admin_username;
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

        $sql = "UPDATE tbl_configuration SET configValue = '".$ldap_host."' WHERE configType = 'ldap_host'";
        $result = $conn->query($sql);
        if ($result === TRUE) {
                echo "Update Successful";
        } else {
                echo "Update Failed";
        }
        $sql = "UPDATE tbl_configuration SET configValue = '".$ldap_dn."' WHERE configType = 'ldap_dn'";
        $result = $conn->query($sql);
        if ($result === TRUE) {
                echo "Update Successful";
        } else {
                echo "Update Failed";
        }

        $conn->close();


}

if (isset($_POST['siteAdminUsername']) && isset($_POST['siteTitle'])){
	update_site_admin();
}
if (isset($_POST['ldapServer']) && isset($_POST['ldapDN'])){
        update_ldap_admin();
}

?>
