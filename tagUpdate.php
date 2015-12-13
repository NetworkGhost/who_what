<?php
//+++++++++++++++++++++++++++++++++++++++++++++++++
//This page is used for updating and retrieving tags
//for a user
//+++++++++++++++++++++++++++++++++++++++++++++++++
include("check_login.php");
include("search.php");
//initialize variables

//Tag variable used for updating tags
$tag = "";
//Stores userID that is passed
$userID = "";
//Stores tag type
$tagType = "";
//Check POST variables to assign values
if (isset($_POST['userID'])) {
	$userID = $_POST['userID'];
}; 
if (isset($_POST['tag'])) {
        $tag = $_POST['tag'];
};
if (isset($_POST['tagType'])) {
        $tagType = $_POST['tagType'];
};
//+++++++++++++++++++++++++++++++++++++++++++++++++
//Insert tags for a given user
//+++++++++++++++++++++++++++++++++++++++++++++++++
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
//Check for mandatory tag fields and insert. 
//Once inserted, echo the new tag set
if (isset($_POST['tag']) && isset($_POST['userID'])){
        update_tags($tag,$userID,$tagType);
	echo get_tags($userID); 
}

?>
