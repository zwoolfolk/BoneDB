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

<h3>Search Bones</h3>
<div>
	<form method="post" action="view_bone.php">
		<fieldset>
			<legend>Bone Information</legend>
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
					<p>Bone Side: 
						<select name="BoneSide">
							<option value=0>All Sides</option>
							<option value='Left'>Left</option>
							<option value='Right'>Right</option>
						</select></p>
					<p>Bone Sex: 
						<select name="BoneSex">
							<option value=0>All Sexes</option>
							<option value='M'>M</option>
							<option value='F'>F</option>
						</select></p>
				<p>Bone Provenance:
					<select name="BoneProvenance">
						<option value=0>All Provenances</option>
						<?php
						if(!($stmt = $mysqli->prepare("SELECT bag_provenance FROM bag"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($bag_provenance)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo '<option value="'. $bag_provenance . '"> ' . $bag_provenance . '</option>\n';
						}
						$stmt->close();
						?>
					</select></p>
		</fieldset>
		<p><input type="submit" value="Search Bones" /></p>
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
			<td>Type</td>
			<td>Side</td>
			<td>Sex</td>
			<td>Bag</td>
			<td>Box</td>
			<td>Provenance</td>
		</tr>
<?php
$qry = "SELECT bone.bone_number, bone.bone_type, bone.side, bone.sex, bag.bag_number, box.box_number, bag.bag_provenance FROM bone JOIN bone_bag ON bone.bone_id = bone_bag.bone_id JOIN bag ON bone_bag.bag_id = bag.bag_id JOIN bag_box ON bag_box.bag_id = bag.bag_id JOIN box ON box.box_id = bag_box.box_id WHERE bone.bone_id IS NOT NULL ";

if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = ? ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND bone.bone_type = ? ";
}
if(!(empty($_POST['BoneSide']))){
	$qry .= "AND bone.side = ? ";
}
if(!(empty($_POST['BoneSex']))){
	$qry .= "AND bone.sex = ? ";
}
if(!(empty($_POST['BagProvenance']))){
	$qry .= "AND bag.bag_provenance = ? ";
}


if(!($stmt = $mysqli->prepare($qry))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}


//To do: clean up
//All params
if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex'])) && !(empty($_POST['BagProvenance']))
	){
	if(!($stmt->bind_param("issss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSide'],$_POST['BoneSex'],$_POST['BagProvenance']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//1 param missing
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("isss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSide'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BagProvenance']))
	){
	if(!($stmt->bind_param("isss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSide'],$_POST['BagProvenance']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSex'])) && !(empty($_POST['BagProvenance']))
	){
	if(!($stmt->bind_param("isss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSex'],$_POST['BagProvenance']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex'])) && !(empty($_POST['BagProvenance']))
	){
	if(!($stmt->bind_param("isss",$_POST['BoneNumber'],$_POST['BoneSide'],$_POST['BoneSex'],$_POST['BagProvenance']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex'])) && !(empty($_POST['BagProvenance']))
	){
	if(!($stmt->bind_param("ssss",$_POST['BoneType'],$_POST['BoneSide'],$_POST['BoneSex'],$_POST['BagProvenance']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//2 param missing
//*** BEGIN HERE: 1 2 missing, 1 3, 1 4... ***
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide']))
	){
	if(!($stmt->bind_param("iss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSide']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("iss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("iss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("sss",$_POST['BoneNumber'],$_POST['BoneType'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//3 param missing
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneType']))
	){
	if(!($stmt->bind_param("is",$_POST['BoneNumber'],$_POST['BoneType']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneSide']))
	){
	if(!($stmt->bind_param("is",$_POST['BoneNumber'],$_POST['BoneSide']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneNumber'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("is",$_POST['BoneNumber'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType'])) && !(empty($_POST['BoneSide']))
	){
	if(!($stmt->bind_param("ss",$_POST['BoneType'],$_POST['BoneSide']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneType'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("ss",$_POST['BoneType'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneSide'])) && !(empty($_POST['BoneSex']))
	){
	if(!($stmt->bind_param("ss",$_POST['BoneSide'],$_POST['BoneSex']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
//4 param missing
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
else if(!(empty($_POST['BoneSide']))){
	if(!($stmt->bind_param("s",$_POST['BoneSide']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}
else if(!(empty($_POST['BoneSex']))){
	if(!($stmt->bind_param("s",$_POST['BoneSex']))
	){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
}





if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($bone_number, $bone_type, $bone_side, $bone_sex, $bag_number, $box_number, $provenance)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}



while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $bone_number . "\n</td>\n<td>\n" . $bone_type . "\n</td>\n<td>\n" . $bone_side . "\n</td>\n<td>\n" . $bone_sex . "\n</td>\n<td>\n" . $bag_number . "\n</td>\n<td>\n" . $box_number . "\n</td>\n<td>\n" . $provenance . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br />










	</body>
</html>