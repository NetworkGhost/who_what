<html lang="en">
<?php
session_start();
if (isset($_SESSION['user']) && ($_SESSION['user'] === "admin")) {
	//Do Nothing, user can access this page.
	include("authenticate.php");
} else {
	//User not allowed. Return user back to index page.
        header("Location: index.php");
        die();
}
include("html_head.php");
?>

<body>

<?php
//Get ldap settings
ldap_settings();
//+++++++++++++++++++++++++++++++++++++++++++++++++
//Get site settings for editing
//+++++++++++++++++++++++++++++++++++++++++++++++++
function get_site_admin() {
        global $site_title, $site_admin_username, $site_admin_password, $site_photo_url,$site_logo_url;
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

	//Select all site settings
        $sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(site_)'";
        $result = $conn->query($sql);
	//Set global site configuration variables
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
                        if ($row["configType"] == "site_logo_url") {
                                $site_logo_url = $row["configValue"];
                        }

                }
        } else {
                echo "0 results";
        }
        $conn->close();


}
//Load site configuration variables
get_site_admin();
?>
<?php
//Include global site header
include("header.php");
?>
<div class="container">
<h1>Configuration</h1>
<h2>Site Administration</h2>
<hr />
<form action="#" method="post" name="site_form" id="site_form" class="form_class" >
<?php
	echo '<div class="form-group">';
        echo 'Site Admin Username: <input type="text" value="'.$site_admin_username.'" id="siteAdminUsername" name="siteAdminUsername" class="form-control" placeholder="'.$site_admin_username.'" required autofocus/>';
	echo 'Site Admin Password: <input type="password" value="'.$site_admin_password.'" id="siteAdminPassword" name="siteAdminPassword" class="form-control" placeholder="'.$site_admin_password.'" required autofocus/>';
	echo 'Confirm Password: <input type="password" value="'.$site_admin_password.'" id="conf_siteAdminPassword" name="conf_siteAdminPassword" class="form-control" placeholder="'.$site_admin_password.'" required autofocus/>';

	echo '<a style="cursor: pointer;" title="URL for directory photo. Photo URL should end in <SAMAccountName>.jpg" data-toggle="tooltip">User Photo URL: </a><input title="URL for directory photo. Photo URL should end in <SAMAccountName>.jpg" data-toggle="tooltip"  type="text" value="'.$site_photo_url.'" id="site_photo_url" name="site_photo_url" class="form-control" placeholder="'.$site_photo_url.'" required autofocus/>';
	echo 'Site Logo URL: <input type="text" value="'.$site_logo_url.'" id="site_logo_url" name="site_logo_url" class="form-control" placeholder="'.$site_logo_url.'" required autofocus/>';

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
        echo 'Server IP: <input type="text" id="ldapServer" name="ldapServer" class="form-control" value="'.$ldap_host.'" placeholder="'.$ldap_host.'" required autofocus/>';
	echo 'LDAP DN: <input type="text" id="ldapDN" name="ldapDN" class="form-control" placeholder="'.$ldap_dn.'" value="'.$ldap_dn.'" required autofocus/>';
	echo 'LDAP Filter: <input type="text" id="ldapFilter" name="ldapFilter" class="form-control" value="'.$ldap_filter.'" placeholder="'.$ldap_filter.'" required autofocus/>';
	echo 'LDAP Bind Username: <input type="text" id="ldapUsername" name="ldapUsername" class="form-control" value="'.$ldap_username.'" placeholder="'.$ldap_username.'" required autofocus/>';
	echo 'LDAP Bind Password: <input type="password" id="ldapPassword" name="ldapPassword" class="form-control" value="'.$ldap_password.'" placeholder="'.$ldap_password.'" required autofocus/>';
        echo '</div>';

?>

<div class="form-group">
        <input type="button" name="submit_id" id="btn_ldap" value="Submit" class="btn btn-primary" />
</div>
</form>


</div>
</body>
</html>

