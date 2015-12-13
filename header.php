<?PHP
session_start();
#echo '<p class="bg-success">Welcome '.$_SESSION['user'].' <a href="./login.php?out=1">logout</a></p>';
$header_site_title = "W^2 Database";
$header_site_logo_url = "w2logo.JPG";
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
                        if ($row["configType"] == "site_logo_url" && !empty($row["configValue"])) {
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
<table style='width: 100%'>
<tr>
<td>

	<div style='padding:5px;'>
<?PHP
	get_site_header_info();
	echo '<a class="navbar-brand" style="padding-top: 10px; height: 29px;margin: 0px;" href="#">';
	if (strlen($header_site_logo_url) == 0) {
		echo '<img alt="W^2" width=40 height=40 src="'.$header_site_logo_url.'">';
	} else {
        	echo '<img alt="Brand" width=40 height=40 src="'.$header_site_logo_url.'">';
	}
      	echo '</a>';

      	echo '<a class="navbar-brand " style="color: white;" href="#">'.$header_site_title.'</a>'; 
?>
<ul class="nav nav-pills">
        <li class="active"><a href="index.php">Find People</a></li>
<?PHP
	if (isset($_SESSION['user']) && ($_SESSION['user'] === "admin")) {
        echo '<li class="active"><a href="admin.php">Admin</a></li>';
	}
?>
        <li class="active"><a href="./login.php?out=1">Logout</a></li>
</ul>
	</div>
</td>
	<td style="text-align: right; padding-right: 30px;" ><?php echo $_SESSION['user'];  ?></td>
</tr>
</table>
</div>
