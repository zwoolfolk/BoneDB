<?php
ini_set('display_errors', 'On');
$mysqli = new mysqli("localhost","root","root","bone_db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
	<title>Bones</title>
	</head>
	<body>
		<h2>Paige is the coolest</h2>
	<ul>
		<li>
			<a href="index.php">Home</a>
		</li>
		<li>
			<a href="view_bone.php">View Bones</a>
		</li>
		<li>
			<a href="view_ancestry.php">View Ancestry</a>
		</li>
		<li>
			<a href="view_picture.php">View Pictures</a>
		</li>
		<li>
			<a href="view_individual.php">View Individuals</a>
		</li>
		<li>
			<a href="view_sample.php">View Samples</a>
		</li>
		<li>
			<a href="view_age.php">View Age</a>
		</li>
	</ul>
<br />

<h3>Search Individuals</h3>
<div>
	<form method="post" action="view_individual.php">
		<fieldset>
			<legend>Individual Information</legend>
				<p>Bone Number: 
					<select name="BoneNumber">
						<option value=0>All Bones</option>
						<?php
						if(!($stmt = $mysqli->prepare("SELECT bone_number FROM bone"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($bone_number)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo '<option value="'. $bone_number . '"> ' . '# ' . $bone_number . '</option>\n';
						}
						$stmt->close();
						?>
					</select></p>
				<p>Individual Number:
					<select name="IndividualNumber">
						<option value=0>All Individuals</option>
						<?php
						if(!($stmt = $mysqli->prepare("SELECT individual_id FROM individual"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($individual_number)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo '<option value="'. $individual_number . '"> ' . '# ' . $individual_number . '</option>\n';
						}
						$stmt->close();
						?>
					</select></p>
		</fieldset>
		<p><input type="submit" value="Search Individuals" /></p>
	</form>
</div>
<br />







<div>
	<table>
		<tr>
			<td><strong>Bones</strong></td>
		</tr>
		<tr>
			<td>Bone#</td>
			<td>Individual#</td>
		</tr>
<?php
$qry = "SELECT bone.bone_number, individual.individual_id FROM bone JOIN bone_individual ON bone_individual.bone_id = bone.bone_id JOIN individual ON individual.individual_id = bone_individual.individual_id WHERE bone.bone_id IS NOT NULL ";

if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = ? ";
}
if(!(empty($_POST['IndividualNumber']))){
	$qry .= "AND individual.individual_id = ? ";
}



if(!($stmt = $mysqli->prepare($qry))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}


//To do: clean up
//All params
if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['IndividualNumber'])) 
	){
	if(!($stmt->bind_param("ii",$_POST['BoneNumber'],$_POST['IndividualNumber']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//1 param missing
else if(!(empty($_POST['BoneNumber']))){
	if(!($stmt->bind_param("i",$_POST['BoneNumber']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['IndividualNumber']))){
	if(!($stmt->bind_param("i",$_POST['IndividualNumber']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}





if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($bone_number, $individual_number)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}



while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $bone_number . "\n</td>\n<td>\n" . $individual_number . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br />










	</body>
</html>