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
	<form method="post" action="view_age.php">
		<fieldset>
			<legend>Age Information</legend>
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
				<p>Age Type:
					<select name="AgeType">
						<option value=0>All Types</option>
						<?php
						$stmt = $pdo->prepare("SELECT age_type FROM age");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['age_type'] . '"> ' . $row['age_type'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Age Range:
					<select name="AgeRange">
						<option value=0>All Ranges</option>
						<?php
						$stmt = $pdo->prepare("SELECT age_range FROM age");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['age_range'] . '"> ' . $row['age_range'] . '</option>\n';
						}
						?>
					</select></p>
		</fieldset>
		<p><input type="submit" value="Search Ages" /></p>
	</form>
</div>
<br />







<div>
	<table>
		<tr>
			<th>Bone#</th>
			<th>Age Type</th>
			<th>Range</th>
		</tr>
<?php
$qry = "SELECT bone.bone_number, age.age_type, age.age_range FROM bone LEFT JOIN bone_age ON bone.bone_id = bone_age.bone_id LEFT JOIN age ON age.age_id = bone_age.age_id WHERE bone.bone_id IS NOT NULL ";


if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['AgeType']))){
	$qry .= "AND age.age_type = :ageType ";
}
if(!(empty($_POST['AgeRange']))){
	$qry .= "AND age.age_range = :ageRange ";
}

$stmt = $pdo->prepare($qry);


if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['AgeType']))){
	$stmt->bindParam(':ageType', $_POST['AgeType']);
}
if(!(empty($_POST['AgeRange']))){
	$stmt->bindParam(':ageRange', $_POST['AgeRange']);
}



$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['age_type'] . "\n</td>\n<td>\n" . $row['age_range'] . "\n</td>\n</tr>";
}

?>
	</table>
</div>
<br />










	</body>
</html>