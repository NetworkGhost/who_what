<?php
$page_site_title = "W^2 Database";
function get_site_title_info() {
        global $page_site_title;
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

        $sql = "SELECT * from tbl_configuration WHERE configType REGEXP '(site_title|site_logo)'";
        $result = $conn->query($sql);

        if ($result->num_rows > 1) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                        if ($row["configType"] == "site_title" && !empty($row["configValue"])) {
                                $page_site_title = $row["configValue"];
                        }
                }
        } else {
                //echo "0 results";
        }
        $conn->close();


}
get_site_title_info();
echo '<head>';
echo '<meta charset="utf-8">';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->';
echo '<meta name="description" content="">';
echo '<meta name="author" content="">';
echo '<title>'.$page_site_title.'</title>';
echo '<script src="jquery-2.1.4.min.js"></script>';
echo '<script src="bootstrap-3.3.5-dist/js/bootstrap.js"></script>';
echo '<link href="./bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">';

if (isset($_SESSION['user']) && ($_SESSION['user'] === "admin")) {
	echo '<script src="admin.js"></script>';
} 
else {
	echo '<script src="site_actions.js"></script>';
}

echo '</head>';

?>
