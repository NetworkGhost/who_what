<?PHP
session_start();
#echo '<p class="bg-success">Welcome '.$_SESSION['user'].' <a href="./login.php?out=1">logout</a></p>';
$header_site_title = "";
$header_site_logo_url = "";
function get_site_header_info() {
        global $header_site_title, $header_site_logo_url;
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
                        if ($row["configType"] == "site_title") {
                                $header_site_title = $row["configValue"];
                        }
                        if ($row["configType"] == "site_logo_url") {
                                $header_site_logo_url = $row["configValue"];
                        }
                }
        } else {
                //echo "0 results";
        }
        $conn->close();


}

?>
<div style='background-color: #1874CD; width: 100%;' class="navbar-header">
	<div style='padding:5px;'>
<?PHP
	get_site_header_info();
	echo '<a class="navbar-brand" style="padding-top: 10px; height: 29px;margin: 0px;" href="#">';
        echo '<img alt="Brand" src="'.$header_site_logo_url.'">';
      	echo '</a>';

      	echo '<a class="navbar-brand " style="color: white;" href="#">'.$header_site_title.'</a>'; 
?>
<ul class="nav nav-pills">
        <li class="active"><a href="index.php">Find People</a></li>
<?PHP
	if (isset($_SESSION['user']) && ($_SESSION['user'] === "admin" || strpos($_SESSION['user'],"joe") >= 0)) {
        echo '<li class="active"><a href="admin.php">Admin</a></li>';
	}
?>
        <li class="active"><a href="./login.php?out=1">Logout</a></li>
</ul>
	</div>
</div>
