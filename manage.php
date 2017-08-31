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



<h3>Update Bone</h3>
<div>
<form method="post" action="update_bone.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber">
					<?php
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, sex FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - '; 
							if($row['sex'] == 'NULL'){
								echo ' ' . '</option>\n';
							} else {
								echo $row['sex'] . '</option>\n';
							}
					}
					?>
				</select></p>
			<p>Bone Sex: 
				<select name="BoneSex">
					<option value=0>Unknown/Blank</option>
					<option value='M'>M</option>
					<option value='F'>F</option>
				</select></p>
			<p>Bone Type:
				<select name="BoneType">
					<?php
					$stmt = $pdo->prepare("SELECT bone_type FROM type");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Update Bone" /></p>
</form>
</div>
<br />




<h3>Add New Bone Type</h3>
<div>
<form method="post" action="add_bone_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Bone Type: <input type="text" name="BoneType" /></p>
	</fieldset>
	<p><input type="submit" value="Add Bone Type" /></p>
</form>
</div>
<br />

<h3>Remove Bone Type</h3>
<div>
<form method="post" action="remove_bone_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Bone Type:
				<select name="BoneType">
					<?php
					$stmt = $pdo->prepare("SELECT bone_type FROM type");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Bone Type" /></p>
</form>
</div>
<br />



<h3>Add Bone Ancestry</h3>
<div>
<form method="post" action="add_ancestry.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber">
					<?php
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, ancestry.ancestry_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id LEFT JOIN bone_ancestry ON bone.bone_id = bone_ancestry.bone_id LEFT JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - ' . $row['ancestry_type'] . '</option>\n';
					}
					?>
				</select></p>
			<p>Bone Ancestry: 
				<select name="BoneAncestry">
					<?php
					$stmt = $pdo->prepare("SELECT ancestry_id, ancestry_type FROM ancestry");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){					
						echo '<option value="'. $row['ancestry_id'] . '"> ' . $row['ancestry_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Add Ancestry" /></p>
</form>
</div>
<br />




<h3>Remove Bone Ancestry</h3>
<div>
<form method="post" action="remove_ancestry.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="JoinID">
					<?php
					$stmt = $pdo->prepare("SELECT join_id, bone_number, type.bone_type, ancestry.ancestry_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id LEFT JOIN bone_ancestry ON bone.bone_id = bone_ancestry.bone_id LEFT JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id WHERE ancestry.ancestry_type IS NOT NULL");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['join_id'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - ' . $row['ancestry_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Ancestry" /></p>
</form>
</div>
<br />




<h3>Add New Ancestry Type</h3>
<div>
<form method="post" action="add_ancestry_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Ancestry Type: <input type="text" name="AncestryType" /></p>
	</fieldset>
	<p><input type="submit" value="Add Ancestry Type" /></p>
</form>
</div>
<br />

<h3>Remove Ancestry Type</h3>
<div>
<form method="post" action="remove_ancestry_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Ancestry Type:
				<select name="AncestryType">
					<?php
					$stmt = $pdo->prepare("SELECT ancestry_type FROM ancestry");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['ancestry_type'] . '"> ' . $row['ancestry_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Ancestry Type" /></p>
</form>
</div>
<br />




<h3>Add New Sample</h3>
<div>
<form method="post" action="add_sample.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber"> ****START HERE****
					<?php
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, sex FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - '; 
							if($row['sex'] == 'NULL'){
								echo ' ' . '</option>\n';
							} else {
								echo $row['sex'] . '</option>\n';
							}
					}
					?>
				</select></p>
			<p>Bone Sex: 
				<select name="BoneSex">
					<option value=0>Unknown/Blank</option>
					<option value='M'>M</option>
					<option value='F'>F</option>
				</select></p>
			<p>Bone Type:
				<select name="BoneType">
					<?php
					$stmt = $pdo->prepare("SELECT bone_type FROM type");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Update Bone" /></p>
</form>
</div>
<br />



******Add/remove sample (create now row in sample with appropriate type, create new bone_sample assoc)
******Add/remove aging type
******Add/remove aging range
******Add/remove bone to aging

******Add/edit comments to X

******Add individual (?)
******Add bone to individual (?)
******Add bone (?)
******Add picture (?)

	</body>
</html>