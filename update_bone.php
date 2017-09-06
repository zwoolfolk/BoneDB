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
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	</head>
	<body>
		<h2>Bone Database</h2>
	<ul>
		<li>
			<a href="index.php">Home</a>
		</li>
		<li>
			<a href="analyses.php">Analyses</a>
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




<?php
//Update sex and side
$qry = "UPDATE bone SET sex = :boneSex, side = :boneSide WHERE bone_number = :boneNumber";

$stmt = $pdo->prepare($qry);

$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);

if($_POST['BoneSex'] == '0'){
	$a = NULL;
	$stmt->bindParam(':boneSex', $a);
} else { 
	$stmt->bindParam(':boneSex', $_POST['BoneSex']);
}

if($_POST['BoneSide'] == '0'){
	$a = NULL;
	$stmt->bindParam(':boneSide', $a);
} else { 
	$stmt->bindParam(':boneSide', $_POST['BoneSide']);
}

$stmt->execute();

echo "<div>Updated " . $stmt->rowCount() . " records.</div><br />";



//Remove current bone-type association
$qry2 = "DELETE FROM bone_type WHERE bone_id = (SELECT bone_id FROM bone WHERE bone_number = :boneNumber)";

$stmt2 = $pdo->prepare($qry2);

$stmt2->bindParam(':boneNumber', $_POST['BoneNumber']);

$stmt2->execute();

echo "<div>Deleted " . $stmt2->rowCount() . " records.</div><br />";


//Add new bone-type association
$qry3 = "INSERT INTO bone_type (bone_id, type_id) VALUES ((SELECT bone_id FROM bone WHERE bone_number = :boneNumber), (SELECT type_id FROM type WHERE bone_type = :boneType))";

$stmt3 = $pdo->prepare($qry3);

$stmt3->bindParam(':boneNumber', $_POST['BoneNumber']);
$stmt3->bindParam(':boneType', $_POST['BoneType']);

$stmt3->execute();

echo "<div>Added " . $stmt3->rowCount() . " records.</div><br />";
?>







	</body>
</html>