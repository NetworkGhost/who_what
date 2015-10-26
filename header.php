<?PHP
session_start();
echo '<p class="bg-success">Welcome '.$_SESSION['user'].' <a href="./login.php?out=1">logout</a></p>';
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

