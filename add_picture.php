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
//upload file
//***To do: add extension restrictions etc
$uploads_dir = __DIR__ . '/pictures/';
$temp = explode(".", $_FILES['PictureFile']['name']);
$newfilename = $_POST['PictureNumber'] . "." . end($temp);

if(file_exists($uploads_dir . $newfilename)){
	echo "<div>A photo with this Picture Number already exists! Upload canceled.</div><br />";
} else {
	if(move_uploaded_file($_FILES['PictureFile']['tmp_name'], $uploads_dir . $newfilename)){
		echo "<div>File upload success.</div><br />";





	//add new entry to picture table
	$qry = "INSERT INTO picture (picture_number, picture_tags) VALUES (:picNumber, :picTags)";

	$stmt = $pdo->prepare($qry);

	$stmt->bindParam(':picNumber', $_POST['PictureNumber']);
	$stmt->bindParam(':picTags', $_POST['PictureTags']);

	$stmt->execute();

	echo "<div>Added " . $stmt->rowCount() . " records.</div><br />";

	//add new entry to bone_picture
	$qry2 = "INSERT INTO bone_picture (bone_id, picture_id) VALUES (:boneID, (SELECT picture_id FROM picture WHERE picture_number = :picNumber))";

	$stmt2 = $pdo->prepare($qry2);

	$stmt2->bindParam(':boneID', $_POST['BoneID']);
	$stmt2->bindParam(':picNumber', $_POST['PictureNumber']);

	$stmt2->execute();

	echo "<div>Added " . $stmt2->rowCount() . " records.</div><br />";
	




	} else {
		echo "Error uploading file.";
	}
}
?>


	</body>
</html>