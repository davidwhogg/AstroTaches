<?php
//Setup Database
$db_sum  = mysql_connect("127.0.0.1","root","SQLgoNOGO");
mysql_select_db('astrotaches', $db_sum);

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
  <div id="container">
	<div id="header">
		<div id="logo">
			<img src="graphics/Logo.png">
		</div>	
	</div>


	<div id="sidebar">
		<?php
		// Login
		echo "<br>";
		if (!isset($_COOKIE["stache"])) {
			echo "<a href = './zoo-login.php'>Login</a>";
		} else {
			echo "<a href = './zoo-logout.php'>Logout</a>";
		}
		echo "<br>";
		?>
		<br>
		<p><h3>Paint over the blue features in the image,
	           then hit submit.</h3></p>
	</div>

	<div id="main">
		
		<?php
			if(isset($_POST['maxopacity'])) {
				if (isset($_COOKIE['id'])) {
					$this_cookie = $_COOKIE['id'];
				} else {
					$this_cookie = 1;
				}

				$maxopacity = $_POST['maxopacity'];
				$myscribbles = $_POST['myscribbles'];
				$pixeldistance = $_POST['pixeldistance'];

				// Set time. This get's used a lot
				$time = new DateTime(null, new DateTimeZone('America/Chicago'));
				$time = date_format($time, 'Y-m-d H:i:s');

				// Classifications directory
				$query = "INSERT INTO classifications (zooniverse_user_id, created_at, updated_at) 
	                                VALUES ($this_cookie, '$time', '$time')";
	            mysql_query($query);

	            echo mysql_error();
				$class_id = mysql_insert_id();

				$query = "INSERT INTO annotations (maxopacity, myscribbles, pixeldistance) VALUES ($maxopacity, '".$myscribbles."', $pixeldistance)";
				mysql_query($query);
				echo mysql_error();

				?>
					<span style='color: #000;'><br>THANK YOU!<br><br>
						<em>You're helping us understand if this could be a new and better way to extract information from astronomical images.<em><br><br>
							
					</span>
					<span style='color: #000;'>Clear Skies,<br>
						David Hogg, Phil Marshall, Pamela L. Gay, & Stuart Lowe</span><br><br>
					
					<span style="text-align: center;"><a href="http://zooniverse.org"><img src="graphics/ZooniverseSquare.png" width="100px"></a></span>
					
				<?php
				die();			
			} else {

		?>
		<div id="AstroTaches-area">
				<div id="AstroTaches"><img src="graphics/test.jpg"></div>
		
			<div id="AstroTaches-Tools">	
				<a href="#" onclick="scribble.setPaintbrush();"><img src="graphics/pencil.png" width="60px"></a>		
				<a href="#" onclick="scribble.setEraser();"><img src="graphics/eraser.png" width="60px"></a>
				<a href="#" onclick="scribble.clearImage();"><img src="graphics/edit_clear.png" width="60px"></a>
 
				<form id="send" name="send" action="" method="post">
				   <input type="hidden" name="myscribbles" value="" />
				   <input type="hidden" name="maxopacity" value="" />
				   <input type="hidden" name="pixeldistance" value="" />
				   <input class="submit" type="submit" value="Submit" />
				</form>
			</div>
			
		</div>

	<?php } ?>
	</div>
	
	<div id="clear">


	<div id="footer">
		Created at <a href="http://dotastronomy.com/2011-conference">Dot Astronomy
		3</a>, Oxford, April 2011. Unmodelled HST gravitational lens test image from
		Marshall et al, in preparation. Source code visible at <a
		href="https://github.com/hogghogg/AstroTaches">GitHub</a>.
	</div>
  </div>