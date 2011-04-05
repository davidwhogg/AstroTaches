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
		       //scribble = new AstroTaches({id:'AstroTaches'});
		   }
		   </script>
</head>

<body>
  <div id="container">
	<div id="header">
		<div id="logo">
			<img src="graphics/Logo.png">
		</div>
		<h2>How useful are paintings of astronomical images?</h2><br>
	</div>


	<div id="sidebar">
		<h2>Paint over the blue features in this image:</h2>
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
		<div id="AstroTaches">
			<img src="examples/HSTJ135754_29-311509_1_sci.jpg">
		
			<div id="AstroTaches-Tools">			
				<a href="javascript:scribble.eraser=true">erase</a>
				<a href="javascript:scribble.eraser=false">paint</a>
			</div>
			
		</div>

		
	</div>
	
	<div id="clear">


	<div id="footer">
		"Created at <a href="http://dotastronomy.com/2011-conference">Dot Astronomy
		3</a>, Oxford, April 2011. Unmodelled HST gravitational lens test image from
		Marshall et al, in preparation. Source code visible at <a
		href="https://github.com/hogghogg/AstroTaches">GitHub</a>.
	</div>
  </div>