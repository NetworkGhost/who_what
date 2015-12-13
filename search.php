<?php
include("check_login.php");
//Include authenticate to set ldap variables 
include("authenticate.php");
ldap_settings();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Adds tag form to page.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function addTagsForm($tagFor) {
        $returns = "";
        #$returns .= '<form method="post" name="'.$tagFor.'_tag_form" id="search_form" class="form_class" style="padding-top: 10px">';
	$returns .= '<div style="padding-left: 0px;" class="showTagForm" id="'.$tagFor.'">';
        $returns .= '<a  type="button" style="cursor: pointer;" class="';
        $returns .= 'btn-default btn-xs">';
        $returns .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Tag</a> ';
	$returns .= '</div>';
        $returns .= '<div class="row" id="'.$tagFor.'_tag_container" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px; text-align: center; background-color: #008AE6;display: none; " >';
        $returns .= '<div class="col-md-4" style="background-color: #008AE6;">';
        $returns .= '    <input id="'.$tagFor.'_tag_Field" type="text" style="font-size: 11px;" class="form-control input-sm" placeholder="Add Tag">';
        $returns .= '</div>';
	$returns .= '<div class="col-md-3" style="background-color: #008AE6;">';
	$returns .= '<select id="'.$tagFor.'_tag_type" style="padding-right: 2px;font-size: 11px;" name="drop_type">';
	$returns .= '  <option value="role">Role</option>';
	$returns .= '  <option value="project">Project/Initiative</option>';
	$returns .= '  <option value="hobby">Hobby/Interest</option>';
	$returns .= '</select>';
	$returns .= '</div>';
        $returns .= '<div class="col-md-1" style="background-color: #008AE6;">';
        $returns .= '    <button id="'.$tagFor.'" type="button" class="btn btn-default btn-sm submitTag">';
        $returns .= '    <span class="glyphicon glyphicon-tag"></span> Add Tag';
        $returns .= '    </button>';
        $returns .= '</div>';
        $returns .= '</div>';
	$returns .= '<br />';
        #$returns .= '</div>';
        #$returns .= '</form>';
        return $returns;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Retrieves tags for a given user and search string
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function get_tags($userID,$searchString) {
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
                $returns .= '<div style="text-align:left;" >';
		$returns .= '<div style="padding-top: 5px; font-size: 11px;" >';
                $returns .= '<b>Roles: </b>';
                if ($result->num_rows >= 1) {
                	while($row = $result->fetch_assoc()) {
                        	$returns .= '<a  type="button" style="cursor: pointer; font-size: 11px;" class="button_tag ';
                		if ($row["tagType"] === "role" && stripos($row["tag"],$searchString) !== false) {
                        		$returns .= 'btn-success btn-xs">';
                		} elseif ($row["tagType"] === "role") {
                        		$returns .= 'btn-primary btn-xs">';
                		}
                		$returns .= $row["tag"].'</a> ';
                	}
                }
		$returns .= '</div>';
		$returns .= '<div style="padding-top: 5px; font-size: 11px;" >';
                $returns .= '<b>Projects/Workstreams: </b>';
                $sql = "SELECT * from tbl_tags WHERE userID = '".$userID."' AND tagType = 'project'";
                $result = $conn->query($sql);
                if ($result->num_rows >= 1) {
                        while($row = $result->fetch_assoc()) {
                                $returns .= '<a  type="button" style="font-size: 11px;cursor: pointer;" class="button_tag ';
                                if ($row["tagType"] === "project" && stripos($row["tag"],$searchString) !== false) {
                                        $returns .= 'btn-success btn-xs">';
                                } elseif ($row["tagType"] === "project") {
                                        $returns .= 'btn-warning btn-xs">';
                                }
                                $returns .= $row["tag"].'</a> ';
                        }
                }

                $returns .= '</div>';
		$returns .= '<div style="padding-top:5px ; font-size: 11px;">';
                $returns .= '<b style="padding-top: 10px;">Hobbies/Interests: </b>';
                $sql = "SELECT * from tbl_tags WHERE userID = '".$userID."' AND tagType = 'hobby'";
                if ($result->num_rows >= 1) {
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                $returns .= '<a  type="button" style="cursor: pointer; font-size: 11px;" class="button_tag ';
		if ($row["tagType"] === "hobby" && stripos($row["tag"],$searchString) !== false) {
		        $returns .= 'btn-success btn-xs">'; 
                } elseif ($row["tagType"] === "hobby") {
                        $returns .= 'btn-warning btn-xs">';
                }
                $returns .= $row["tag"].'</a> ';
                }
                }
		$returns .= '</div>';
                $returns .= '</div>';
        return $returns;
        $conn->close();


}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Retrieves top tags from database
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
                $returns .= '<button id="button_tag_'.$row["tag"].'" name="'.$row["tag"].'" type="button" class="btn btn-default btn-xs">'.$row["tag"].'</button> ';
                }
                $returns .= '</div>';
        return $returns;
        } else {
                return "No tags found";
        }

}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Searches for tags first from taga database, if no results 
//then check ldap database for user search match
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
		$returns .= "<div style='padding-bottom: 5px;'>";
		$returns .= "<b>".$result->num_rows."</b> users found for tag \"".$searchString."\"";
		$returns .= "</div>";
                while($row = $result->fetch_assoc()) {
                        $returns .= search($user,$password, $row['userID'],'search',$searchString);
                }
        } else {
		$returns .= search($user,$password,$searchString,'search',$searchString);
                #echo "0 results";
        }
        $conn->close();
        return $returns;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Searches ldap database for a given user
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function search($user, $password, $searchString,$type,$searchString2) {
        global $ldap_host, $ldap_dn, $ldap_username, $ldap_password, $ldap_filter,$user_photo_url;
        ldap_settings();
        if(empty($user) || empty($password)) return false;
        $ldap = ldap_connect('ldaps://'.$ldap_host);
        // verify user and password
	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
        if($bind = @ldap_bind($ldap, $ldap_username, $ldap_password)) {
                $filter = "(&(|(cn=*$searchString*)(uid=$searchString))".$ldap_filter.")";
                $attr = array("memberof");
                $result = ldap_search($ldap, $ldap_dn, $filter) or exit("Unable to search LDAP server");
                $entries = ldap_get_entries($ldap, $result);
                ldap_unbind($ldap);
                $access = 1;
                $returns = "";
                if ($access != 0) {
                        for ($i=0; $i<$entries["count"]; $i++) {
				$returns .= '<tr style="border-bottom: 1pt solid #A9A9A9;">';	
				$returns .= '<td style="vertical-align: top;" width="50px">';
				if (strlen($user_photo_url) == 0) {
                                	$returns .= '<img style="padding-top:3px;" width=30 height=35 src="default_user.jpg" />';
				}
				else {
					$returns .= '<img style="padding-top:3px;" src="'.$user_photo_url.''.$entries[$i]["cn"][0].'" />';
				}
				$returns .= '</td>';
				$returns .= '<td style="vertical-align: top;" width="150px">';
	                        $returns .= '<a  type="button" style="font-size: 11px;cursor: pointer;" class="button_tag ';
              	                $returns .= 'btn-default btn-xs">';
                		$returns .= $entries[$i]["description"][0].'</a> ';
				$returns .= '<br />';
                                $returns .= '<a  type="button" style="font-size: 11px;cursor: pointer;" class="button_title ';
                                $returns .= 'btn-default btn-xs">';
                                $returns .= $entries[$i]["title"][0].'</a> ';
				$returns .= '<br />';
                                $returns .= '<a  type="button" style="font-size: 11px;cursor: pointer;" class="button_phone" href="tel:';
				$returns .= $entries[$i]["telephonenumber"][0];
				$returns .=  '" ';
                                $returns .= 'btn-default btn-xs">';
                                $returns .= $entries[$i]["telephonenumber"][0].'</a> ';
                                $returns .= '<br />';
                                $returns .= '<a  type="button" style="font-size: 11px;cursor: pointer;" class="button_email" href="mailto:';
                                $returns .= $entries[$i]["mail"][0];
                                $returns .=  '" ';
                                $returns .= 'btn-default btn-xs">';
                                $returns .= $entries[$i]["mail"][0].'</a> ';
                                $returns .= '<br />';
				$returns .= '</td>';
				$returns .= '<td>';
                                $returns .= '<div style="padding-bottom: 5px;" id="'.$entries[$i]["cn"][0].'_tags">';
                                $returns .= get_tags($entries[$i]["cn"][0],$searchString2);
                                #$returns .= '<p>'.var_dump($entries[$i]).'</p>';
                                $returns .= '</div>';
                                #if ($type == 'tags') {
                                $returns .= addTagsForm($entries[$i]["cn"][0]);
                                #}
				$returns .= '</td>';
				$returns .= '</tr>';
                        }
                        return $returns;
                } else {
                $returns .= "<div style='padding-bottom: 5px;'>";
                $returns .= "<b>0</b> users found";
                $returns .= "</div>";

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
//Check post variables search functions
if (isset($_POST['searchString'])) {
	$search_string = $_POST['searchString'];
}; 
if (isset($_POST['type'])) {
        $type = $_POST['type'];
};
if (isset($_POST['searchString'])){
	if ($type == 'tags') {
		$results = '<table style="width: 100%">';
		$results .= searchTags($ldap_username,$ldap_password,$search_string,$type);
		$results .= '</table>';
	} else {
		$results = '<table style="width: 100%">';
		$results .= search($ldap_username,$ldap_password,$search_string,$type);
		$results .= '</table>';
	}
}
//Output results of searches
echo $results;
?>
