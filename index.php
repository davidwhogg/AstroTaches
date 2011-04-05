<?php
//Setup Database
$db_sum  = mysql_connect("127.0.0.1","root","SQLgoNOGO");
mysql_select_db('Summary', $db_sum);

// Send the headers
?>
<html><head>
	<link rel='stylesheet' 
          href='./style.css' 
          type='text/css'>

		<!--[if IE]><script src="excanvas.js"></script><![endif]-->
		   <script src="jquery-1.4.2.js" type="text/javascript"></script>
		   <script src="astrotaches.js" type="text/javascript"></script>
		   <script>
		   window.onload = function() {
		       scribble = new AstroTaches({id:'AstroTaches'});
		   }
		   </script>
</head>

<body>
	<div id="header">
		Welcome to Astrotaches. Header goes here.<br>
	</div>


	<div id="sidebar">
		This is a sidebar.
		<?php
		// Login
		if (!isset($_COOKIE["stache"])) {
			echo "<a href = './zoo-login.php'>Login</a>";
		} else {
			echo "<a href = './zoo-logout.php'>Logout</a>";
		}
		echo "<br>";
		?>
	</div>

	<div id="main">
		This is the Canvas<br>
		<div id="AstroTaches">
			<img src="examples/HSTJ135754_29-311509_1_sci.jpg">
		</div>
	</div>
	
	<div id="clear">
<?php
//Footer
echo "This is the Footer<br>";

?>