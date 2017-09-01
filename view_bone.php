<?php
ini_set('display_errors', 'On');
$host 	= 'localhost';
$db 	= 'bone_db';
$user 	= 'root';
$pass 	= 'root';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
	$pdo = new PDO($dsn, $user, $pass, $opt);
} catch (PDOException $e) {
	echo "Connection error: " . $e->getMessage();
}
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
	<title>Bones</title>
	</head>
	<body>
		<h2>Bone Database</h2>
	<ul>
		<li>
			<a href="index.php">Home</a>
		</li>
		<li>
			<a href="manage.php">Manage</a>
		</li>
		<li>
			<a href="view_ancestry.php">View Ancestry</a>
		</li>
		<li>
			<a href="view_age.php">View Age</a>
		</li>
		<li>
			<a href="view_bone.php">View Bones</a>
		</li>
		<li>
			<a href="view_individual.php">View Individuals</a>
		</li>
		<li>
			<a href="view_picture.php">View Pictures</a>
		</li>
		<li>
			<a href="view_sample.php">View Samples</a>
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
						$stmt = $pdo->prepare("SELECT bone_number FROM bone");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Bone Type:
					<select name="BoneType">
						<option value=0>All Types</option>
						<?php
						$stmt = $pdo->prepare("SELECT bone_type FROM type");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
						}
						?>
					</select></p>
					<p>Bone Side: 
						<select name="BoneSide">
							<option value=0>All Sides</option>
							<option value='Unknown'>Unknown/Blank</option>
							<option value='Left'>Left</option>
							<option value='Right'>Right</option>
						</select></p>
					<p>Bone Sex: 
						<select name="BoneSex">
							<option value=0>All Sexes</option>
							<option value='Unknown'>Unknown/Blank</option>
							<option value='M'>M</option>
							<option value='F'>F</option>
						</select></p>
				<p>Bone Provenance:
					<select name="BagProvenance">
						<option value=0>All Provenances</option>
						<?php
						$stmt = $pdo->prepare("SELECT bag_provenance FROM bag");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['bag_provenance'] . '"> ' . $row['bag_provenance'] . '</option>\n';
						}
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
			<th>Bone#</th>
			<th>Type</th>
			<th>Side</th>
			<th>Sex</th>
			<th>Bag</th>
			<th>Box</th>
			<th>Provenance</th>
		</tr>
<?php
$qry = "SELECT bone.bone_number, type.bone_type, bone.side, bone.sex, bag.bag_number, box.box_number, bag.bag_provenance FROM bone JOIN bone_bag ON bone.bone_id = bone_bag.bone_id JOIN bag ON bone_bag.bag_id = bag.bag_id JOIN bag_box ON bag_box.bag_id = bag.bag_id JOIN box ON box.box_id = bag_box.box_id JOIN bone_type ON bone_type.bone_id = bone.bone_id JOIN type ON type.type_id = bone_type.type_id WHERE bone.bone_id IS NOT NULL ";


if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND type.bone_type = :boneType ";
}
if(!(empty($_POST['BoneSide']))){
	if($_POST['BoneSide'] == 'Unknown'){
		$qry .= "AND bone.side IS NULL ";
	} else {
		$qry .= "AND bone.side = :boneSide ";
	}
}
if(!(empty($_POST['BoneSex']))){
	if($_POST['BoneSex'] == 'Unknown'){
		$qry .= "AND bone.sex IS NULL ";
	} else {
		$qry .= "AND bone.sex = :boneSex ";
	}
}
if(!(empty($_POST['BagProvenance']))){
	$qry .= "AND bag.bag_provenance = :bagProvenance ";
}

$stmt = $pdo->prepare($qry);






if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['BoneType']))){
	$stmt->bindParam(':boneType', $_POST['BoneType']);
}
if(!(empty($_POST['BoneSide']))){
	$stmt->bindParam(':boneSide', $_POST['BoneSide']);
}
if(!(empty($_POST['BoneSex']))){
	$stmt->bindParam(':boneSex', $_POST['BoneSex']);
}
if(!(empty($_POST['BagProvenance']))){
	$stmt->bindParam(':bagProvenance', $_POST['BagProvenance']);
}


$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['bone_type'] . "\n</td>\n<td>\n" . $row['side'] . "\n</td>\n<td>\n" . $row['sex'] . "\n</td>\n<td>\n" . $row['bag_number'] . "\n</td>\n<td>\n" . $row['box_number'] . "\n</td>\n<td>\n" . $row['bag_provenance'] . "\n</td>\n</tr>";
}
?>
	</table>
</div>
<br />










	</body>
</html>