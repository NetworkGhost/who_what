<?php

include("authenticate.php");
ldap_settings();
function addTagsForm($tagFor) {
        $returns = "";
        #$returns .= '<form method="post" name="'.$tagFor.'_tag_form" id="search_form" class="form_class" style="padding-top: 10px">';
        $returns .= '<div style="padding-left: 0px; padding-top: 15px; padding-bottom: 15px; text-align: center; " class="row bg-success">';

        $returns .= '<div class="col-md-4">';
        $returns .= '    <input id="'.$tagFor.'_tag_Field" type="text" class="form-control" placeholder="Add Tag">';
        $returns .= '</div>';
	$returns .= '<div class="col-md-3">';
	$returns .= '<select id="'.$tagFor.'_tag_type" style="padding-right: 2px" name="drop_type">';
	$returns .= '  <option value="role">Role</option>';
	$returns .= '  <option value="project">Project/Initiative</option>';
	$returns .= '  <option value="hobby">Hobby/Interest</option>';
	$returns .= '</select>';
	$returns .= '</div>';
        $returns .= '<div class="col-md-1">';
        $returns .= '    <button id="'.$tagFor.'" type="button" class="btn btn-default btn-sm submitTag">';
        $returns .= '    <span class="glyphicon glyphicon-tag"></span> Add Tag';
        $returns .= '    </button>';
        $returns .= '</div>';
        $returns .= '</div>';

        #$returns .= '</div>';
        #$returns .= '</form>';
        return $returns;
}
function get_tags($userID) {
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

        $sql = "SELECT * from tbl_tags WHERE userID = '".$userID."' AND tagType = 'role'";
        $result = $conn->query($sql);
        $returns = "";
                $returns .= '<div style="text-align:left;" class="row">';
                $returns .= '<h5>Roles</h5>';
                if ($result->num_rows >= 1) {
                while($row = $result->fetch_assoc()) {
                        $returns .= '<button  type="button" class="btn ';
                        if ($row["tagType"] === "role") {
                                $returns .= 'btn-primary btn-xs">';
                        }
                $returns .= $row["tag"].'</button> ';
                }
                }
                $returns .= '<h5>Projects</h5>';
                $sql = "SELECT * from tbl_tags WHERE userID = '".$userID."' AND tagType = 'project'";
                $result = $conn->query($sql);
                if ($result->num_rows >= 1) {
                while($row = $result->fetch_assoc()) {
                $returns .= '<button id="tag_button"  type="button" class="btn ';
                $returns .= 'btn-default btn-xs">';
                $returns .= $row["tag"].'</button> ';
                }
                }

                $returns .= '<h5>Hobbies/Interests</h5>';
                $sql = "SELECT * from tbl_tags WHERE userID = '".$userID."' AND tagType = 'hobby'";
                if ($result->num_rows >= 1) {
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                $returns .= '<button type="button" class="btn ';
                if ($row["tagType"] === "hobby") {
                        $returns .= 'btn-warning btn-xs">';
                }
                $returns .= $row["tag"].'</button> ';
                }
                }
                $returns .= '</div>';
        return $returns;
        $conn->close();


}
function topTags() {
        $servername = "127.0.0.1";
        $username = "who_what";
        $password_sql = "who_what";
        $dbname = "who_what";

        // Create connection
        $conn = new mysqli($servername, $username, $password_sql, $dbname);
        // Check connection
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT tag, count(tagID) as total FROM who_what.tbl_tags GROUP BY tag ORDER BY total DESC LIMIT 10;";
        $result = $conn->query($sql);
        $returns = "";
        if ($result->num_rows >= 1) {
                $returns .= '<div style="text-align:left;" class="row">';
                while($row = $result->fetch_assoc()) {
                $returns .= '<button id="button_tag_'.$row["tag"].'" name="'.$row["tag"].'" style="padding: 5px" type="button" class="btn btn-default ">'.$row["tag"].'</button> ';
                }
                $returns .= '</div>';
        return $returns;
        } else {
                return "No tags found";
        }

}
function searchTags($user, $password, $searchString, $type) {
        global $ldap_host, $ldap_dn;
        $servername = "127.0.0.1";
        $username = "who_what";
        $password_sql = "who_what";
        $dbname = "who_what";

        // Create connection
        $conn = new mysqli($servername, $username, $password_sql, $dbname);
        // Check connection
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * from tbl_tags WHERE tag REGEXP '(".$searchString.")' AND tagType NOT LIKE 'public' GROUP BY userID";
        $result = $conn->query($sql);
        $returns = "";
        if ($result->num_rows >= 1) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                        $returns .= search($user,$password, $row['userID'],'search');
                }
        } else {
                echo "0 results";
        }
        $conn->close();
        return $returns;
}
function search($user, $password, $searchString,$type) {
        global $ldap_host, $ldap_dn, $ldap_username, $ldap_passsword, $ldap_filter,$user_photo_url;
        ldap_settings();
        if(empty($user) || empty($password)) return false;
        $ldap = ldap_connect($ldap_host);
        // verify user and password
        if($bind = @ldap_bind($ldap, $ldap_username, $ldap_password)) {
                $filter = "(&(|(cn=*$searchString*)(uid=$searchString))".$ldap_filter.")";
                #return $filter;
                #$filter = "uid=".$searchString;
                #$filter = "sn=Danfor*";
                $attr = array("memberof");
                $result = ldap_search($ldap, $ldap_dn, $filter) or exit("Unable to search LDAP server");
                $entries = ldap_get_entries($ldap, $result);
                ldap_unbind($ldap);
                $access = 1;
                $returns = "";
                if ($access != 0) {
                                $returns .= '<div class="row bg-success" style="padding-top: 15px; padding-bottom: 15px;">';
                                $returns .= '<div class="col-md-1">';
                                $returns .= '<img height="30" width="30" src="jabber.png" />';
                                $returns .= '</div>';
                                $returns .= '<div class="col-md-1">';
                                $returns .= '<img height="30" width="30" src="spark.png" />';
                                $returns .= '</div>';
                                $returns .= '</div>';
                        for ($i=0; $i<$entries["count"]; $i++) {
                                $returns .= '<div class="row" style="padding-top: 10px">';
                                $returns .= '<div class="col-md-1">';
                                $returns .= '<input type="radio" name="jabber_all" value="all">';
                                $returns .= '</div>';

                                $returns .= '<div class="col-md-1"><img src="'.$user_photo_url.''.$entries[$i]["uid"][0].'" />';
                                $returns .= '<div class="row" style="padding-top: 10px">';
                                $returns .= '<div style="text-align:left;" class="col-md-1">';
                                $returns .= '<img  height="30" width="30" src="linkedin.png" style="padding-right: 5px;" />';
                                $returns .= '</div>';
                                $returns .= '<div style= "text-align:left;" class="col-md-1">';
                                $returns .= '<img  height="30" width="30" src="linkedin.png" style="padding-right: 5px;" />';
                                $returns .= '</div>';
                                $returns .= '<div style= "text-align:left;" class="col-md-1">';
                                $returns .= '<img  height="30" width="30"  src="twitter.png" />';
                                $returns .= '</div>';
                                $returns .= '</div>';
                                $returns .= '</div>';
                                $returns .= '<div style "text-align:left;" class="col-md-2">';
                                $returns .= '<p><h4>'.$entries[$i]["cn"][0].'</h4></p>';
                                $returns .= '<p><h5>'.$entries[$i]["title"][0].'</h5></p>';
                                $returns .= '</div>';
                                $returns .= '<div class="col-md-8">';
                                $returns .= '<div style="padding-bottom: 15px;" id="'.$entries[$i]["uid"][0].'_tags">';
                                $returns .= get_tags($entries[$i]["uid"][0]);
                                #$returns .= '<p>'.var_dump($entries[$i]).'</p>';
                                $returns .= '</div>';

                                #if ($type == 'tags') {
                                $returns .= addTagsForm($entries[$i]["uid"][0]);
                                #}
                                $returns .= '</div>';
                                $returns .= '</div>';
                                $returns .= '<hr />';
                        }
                        return $returns;
                } else {
                        // user has no rights
                        return "Results not found";
                }
        } else {
                // invalid name or password
                return "LDAP SERVER: ".$ldap_host." DN: ".$ldap_dn."  Could not bind";

        }
}




$site_title = "";
$site_admin_username = "";
$ldap_host = "";
$ldap_dn = "";
$search_string = "";
$results = "";
$type = "";
if (isset($_POST['searchString'])) {
	$search_string = $_POST['searchString'];
}; 
if (isset($_POST['type'])) {
        $type = $_POST['type'];
};
if (isset($_POST['searchString'])){
	if ($type == 'tags') {
		$results = searchTags($ldap_username,$ldap_password,$search_string,$type);
	} else {
		$results = search($ldap_username,$ldap_password,$search_string,$type);
	}
}
echo $results;
?>
