<?php
include("search.php");
$tag = "";
$userID = "";
$tageType = "";
if (isset($_POST['userID'])) {
	$userID = $_POST['userID'];
}; 
if (isset($_POST['tag'])) {
        $tag = $_POST['tag'];
};
if (isset($_POST['tagType'])) {
        $tagType = $_POST['tagType'];
};

function update_tags($tag,$userID,$tagType) {
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

        $sql = "INSERT into tbl_tags (tag,tagType,userID) VALUES ('".$tag."','".$tagType."','".$userID."')";
        $result = $conn->query($sql);
        $conn->close();


}
if (isset($_POST['tag']) && isset($_POST['userID'])){
        update_tags($tag,$userID,$tagType);
	echo get_tags($userID); 
}

?>
