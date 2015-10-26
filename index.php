
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>W^2 Login Page</title>
    <link href="./bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="jquery-2.1.4.min.js"></script>
    <script src="site_actions.js"></script>

</head>
<body>

<?php
session_start();
if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        die();
}
?>
<div class="container">
<?php
include("header.php");
?>
<div class="row">
  <div class="col-md-8"></div>
  <div class="col-md-4"></div>
</div>
<h3  style="padding-top: 20px">Search By Tag</h3>
<form name="search_form_tags" id="search_form_tags" class="form_class" >
<div style="text-align: center; " class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
    <input id="searchField_tags" type="text" class="form-control" placeholder="Search"> 
    </div>
    <div class="col-md-1">
    <button id="btn_search_tags" type="button" class="btn btn-default btn-sm">
    <span class="glyphicon glyphicon-search"></span> Search By Tag 
    </button>
    </div>
</div>
</form>
<h3>Search By Name</h3>
<form name="search_form_name" id="search_form_name" class="form_class" >
<div style="text-align: center; " class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
    <input id="searchField_name" type="text" class="form-control" placeholder="Search By Name">
    </div>
    <div class="col-md-1">
    <button id="btn_search_name" type="button" class="btn btn-default btn-sm">
    <span class="glyphicon glyphicon-search"></span> Search By Name
    </button>
    </div>
</div>
</form>

<p class="bg-info"><h4>Top 10 Tags</h4></p>
<?php
include("search.php");
        echo topTags();
?>

<div style="padding-top: 15px" id="searchResults"><p class="bg-info"><h5>Search for someone's name to get started</h5></p></div>
</div>
</body>
</html>
