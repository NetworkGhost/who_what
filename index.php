<html lang="en">
<?php
//Redirect user if not logged in
include("check_login.php");
include("html_head.php");
?>

<body>

<?php
//Include central page header
include("header.php");
?>

<div class="container">
<div style="padding-top: 10px;" class="row">
  <div class="col-md-8"></div>
  <div class="col-md-4"></div>
</div>

<?php
//include search functions and echo the top tags
if (isset($_SESSION['user']) && ($_SESSION['user'] === "admin")) {
        echo 'Admin user cannot execute search functions.';
} else {
        echo '<form name="search_form_tags" id="search_form_tags" class="form_class" >';
        echo '<div style="text-align: center; padding-top: 10px; " class="row">';
        echo '    <div class="col-md-6">';
        echo '    <input id="searchField_tags" type="text" class="form-control" placeholder="Search">';
        echo '    </div>';
        echo '   <div class="col-md-1">';
        echo '    <button id="btn_search_tags" type="button" class="btn btn-default btn-sm">';
        echo '    <span class="glyphicon glyphicon-search"></span> Search';
        echo '    </button>';
        echo '    </div>';
        echo '</div>';
        echo '</form>';

        echo '<p class="bg-info"><h4>Top Tags</h4></p>';

	include("search.php");
        echo topTags();
	echo '<div style="display:none;" id="loader"><img style="margin-left: auto;margin-right: auto;display: block;" src="Searching.gif"></div>';
	echo '<div style="padding-top: 15px" id="searchResults">';
	echo '<p id="get_started" class="bg-info"><h5>Search to get started</h5></p>';

}
?>

</div>
</div>
</body>
</html>
