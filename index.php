<?php
//Setup Database
$db_sum  = mysql_connect("127.0.0.1","root","SQLgoNOGO");
mysql_select_db('Summary', $db_sum);

// Send the headers
?>
<html><header>
	<link rel='stylesheet' 
          href='./style.css' 
          type='text/css'>
</header>

<?php
echo "Welcome to Astrotaches. Header goes here.<br>";

// Sidebar
echo "This is a sidebar.<br>";

// Login
if (!isset($_COOKIE["stache"])) {
	echo "<a href = './zoo-login.php'>Login</a>";
} else {
	echo "<a href = './zoo-logout.php'>Logout</a>";
}
echo "<br>";

// Canvas
echo "This is the Canvas<br>";

//Footer
echo "This is the Footer<br>";

?>