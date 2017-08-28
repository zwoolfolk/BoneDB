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

<h3>Search Ancestry</h3>
<div>
	<form method="post" action="view_ancestry.php">
		<fieldset>
			<legend>Ancestry Information</legend>
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
				<p>Bone Type:
					<select name="BoneType">
						<option value=0>All Types</option>
						<?php
						if(!($stmt = $mysqli->prepare("SELECT bone_type FROM bone"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($bone_type)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo '<option value="'. $bone_type . '"> ' . $bone_type . '</option>\n';
						}
						$stmt->close();
						?>
					</select></p>
				<p>Ancestry Type:
					<select name="AncestryType">
						<option value=0>All Types</option>
						<?php
						if(!($stmt = $mysqli->prepare("SELECT ancestry_type FROM ancestry"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($ancestry_type)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo '<option value="'. $ancestry_type . '"> ' . $ancestry_type . '</option>\n';
						}
						$stmt->close();
						?>
					</select></p>
		</fieldset>
		<p><input type="submit" value="Search Ancestry" /></p>
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
			<td>Bone Type</td>
			<td>Side</td>
			<td>Anc Type</td>
		</tr>
<?php
$qry = "SELECT bone.bone_number, bone.bone_type, bone.side, ancestry.ancestry_type FROM bone JOIN bone_ancestry ON bone_ancestry.bone_id = bone.bone_id JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id WHERE bone.bone_id IS NOT NULL ";

if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = ? ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND bone.bone_type = ? ";
}
if(!(empty($_POST['AncestryType']))){
	$qry .= "AND ancestry.ancestry_type = ? ";
}


if(!($stmt = $mysqli->prepare($qry))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}


//To do: clean up
//All params
if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['AncestryType'])) 
	){
	if(!($stmt->bind_param("iss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['AncestryType']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//1 param missing
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) 
	){
	if(!($stmt->bind_param("is",$_POST['BoneNumber'],$_POST['BoneType']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['AncestryType'])) 
	){
	if(!($stmt->bind_param("is",$_POST['BoneNumber'],$_POST['AncestryType']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType'])) && !(empty($_POST['AncestryType'])) 
	){
	if(!($stmt->bind_param("ss",$_POST['BoneType'],$_POST['AncestryType']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//2 param missing
else if(!(empty($_POST['BoneNumber']))){
	if(!($stmt->bind_param("i",$_POST['BoneNumber']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType']))){
	if(!($stmt->bind_param("s",$_POST['BoneType']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['AncestryType']))){
	if(!($stmt->bind_param("s",$_POST['AncestryType']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}




if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($bone_number, $bone_type, $bone_side, $ancestry_type)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}



while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $bone_number . "\n</td>\n<td>\n" . $bone_type . "\n</td>\n<td>\n" . $bone_side . "\n</td>\n<td>\n" . $ancestry_type . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br />










	</body>
</html>