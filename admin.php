<?php
session_start();
if (isset($_SESSION['user']) && (!$_SESSION['user'] === "admin" || strpos($_SESSION['user'],"joe") < 0)) {
        header("Location: index.php");
        die();


}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>W^2 Login Page</title>
    <script src="jquery-2.1.4.min.js"></script>
    <script src="admin.js"></script>
    <link href="./bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$ldap_host = "";
$ldap_dn = "";
$site_title = "";
$site_admin_username = "";
$site_admin_password = "";
$site_photo_url = "";
function get_site_admin() {
        global $site_title, $site_admin_username, $site_admin_password, $site_photo_url;
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

        $sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(site_)'";
        $result = $conn->query($sql);

        if ($result->num_rows > 1) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                        if ($row["configType"] == "site_admin_username") {
                                $site_admin_username = $row["configValue"];
                        }
                        if ($row["configType"] == "site_title") {
                                $site_title = $row["configValue"];
                        }
                        if ($row["configType"] == "site_admin_password") {
				$site_admin_password = $row["configValue"];
                        }
			if ($row["configType"] == "site_photo_url") {
				$site_photo_url = $row["configValue"];
			}
                }
        } else {
                echo "0 results";
        }
        $conn->close();


}








function get_ldap_settings() {
        global $ldap_host, $ldap_dn;
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

        $sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(ldap_host|ldap_dn)'";
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
                }
        } else {
                echo "0 results";
        }
        $conn->close();


}
get_ldap_settings();
get_site_admin();
?>
<div class="container">
<?php
include("header.php");
?>
<h1>Configuration</h1>
<h2>Site Administration</h2>
<hr />
<form action="#" method="post" name="site_form" id="site_form" class="form_class" >
<?php
	echo '<div class="form-group">';
        echo 'Site Admin Username: <input type="text" value="'.$site_admin_username.'" id="siteAdminUsername" name="siteAdminUsername" class="form-control" placeholder="'.$site_admin_username.'" required autofocus/>';
	echo 'Site Admin Password: <input type="password" value="'.$site_admin_password.'" id="siteAdminPassword" name="siteAdminPassword" class="form-control" placeholder="'.$site_admin_password.'" required autofocus/>';
	echo 'Confirm Password: <input type="password" value="'.$site_admin_password.'" id="conf_siteAdminPassword" name="conf_siteAdminPassword" class="form-control" placeholder="'.$site_admin_password.'" required autofocus/>';

	echo 'User Photo URL: <input type="text" value="'.$site_photo_url.'" id="site_photo_url name="site_photo_url" class="form-control" placeholder="'.$site_photo_url.'" required autofocus/>';

	echo '</div>';
	echo '<div class="form-group">';
        echo 'Site Title: <input type="text" value="'.$site_title.'" id="siteTitle" name="siteTitle" class="form-control" placeholder="'.$site_title.'" required autofocus/>';
	echo '</div>';

?>
<div class="form-group">
	<input type="button" name="submit_id" id="btn_site" value="Submit" class="btn btn-primary" />
</div>
</form>
<h2>LDAP Settings</h2>
<hr />
<form action="#" method="post" name="ldap_form" id="ldap_form" class="form_class" >
<?php
	echo '<div class="form-group">';
        echo 'Server IP: <input type="text" id="ldapServer" name="ldapServer" class="form-control" placeholder="'.$ldap_host.'" required autofocus/>';
	echo 'LDAP DN: <input type="text" id="ldapDN" name="ldapDN" class="form-control" placeholder="'.$ldap_dn.'" required autofocus/>';
        echo '</div>';

?>

<div class="form-group">
        <input type="button" name="submit_id" id="btn_ldap" value="Submit" class="btn btn-primary" />
</div>
</form>


</div>
</body>
</html>

