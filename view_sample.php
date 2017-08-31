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
	<form method="post" action="view_sample.php">
		<fieldset>
			<legend>Sample Information</legend>
				<p>Sample Number: 
					<select name="SampleNumber">
						<option value=0>All Samples</option>
						<?php
						$stmt = $pdo->prepare("SELECT sample_id FROM sample ORDER BY sample_id");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['sample_id'] . '"> ' . $row['sample_id'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Sample Type: 
					<select name="SampleType">
						<option value=0>All Types</option>
						<?php
						$stmt = $pdo->prepare("SELECT sample_type FROM sample");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['sample_type'] . '"> ' . $row['sample_type'] . '</option>\n';
						}
						?>
					</select></p>
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
		</fieldset>
		<p><input type="submit" value="Search Samples" /></p>
	</form>
</div>
<br />







<div>
	<table>
		<tr>
			<th>Sample#</th>
			<th>SType</th>
			<th>Bone#</th>
			<th>BType</th>
		</tr>
<?php
$qry = "SELECT sample.sample_id, sample.sample_type, bone.bone_number, type.bone_type FROM sample JOIN bone_sample ON sample.sample_id = bone_sample.sample_id JOIN bone ON bone.bone_id = bone_sample.bone_id JOIN bone_type ON bone_type.bone_id = bone.bone_id JOIN type ON type.type_id = bone_type.type_id WHERE bone.bone_id IS NOT NULL ";


if(!(empty($_POST['SampleNumber']))){
	$qry .= "AND sample.sample_id = :sampleNum ";
}
if(!(empty($_POST['SampleType']))){
	$qry .= "AND sample.sample_type = :sampleType ";
}
if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND type.bone_type = :boneType ";
}



$stmt = $pdo->prepare($qry);





if(!(empty($_POST['SampleNumber']))){
	$stmt->bindParam(':sampleNum', $_POST['SampleNumber']);
}
if(!(empty($_POST['SampleType']))){
	$stmt->bindParam(':sampleType', $_POST['SampleType']);
}
if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['BoneType']))){
	$stmt->bindParam(':boneType', $_POST['BoneType']);
}




$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo "<tr>\n<td>\n" . $row['sample_id'] . "\n</td>\n<td>\n" . $row['sample_type'] . "\n</td>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['bone_type'] . "\n</td>\n</tr>";
}

?>
	</table>
</div>
<br />










	</body>
</html>