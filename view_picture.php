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
	if( (!(empty($_POST['PictureID']))) && (!(empty($_POST['PictureTags']))) ){
		$updateNoteQry = "UPDATE picture SET picture_tags = :pictureTags WHERE picture_id = :pictureID";
		$updateNoteStmt = $pdo->prepare($updateNoteQry);
		$updateNoteStmt->bindParam(':pictureID', $_POST['PictureID']);
		$updateNoteStmt->bindParam(':pictureTags', $_POST['PictureTags']);
		$updateNoteStmt->execute();
	}
?>

<h3>Search Pictures</h3>
<div>
	<form method="post" action="view_picture.php">
		<fieldset>
			<legend>Picture Information</legend>
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
				<p>Picture Number: 
					<select name="PictureNumber">
						<option value=0>All Pictures</option>
						<?php
						$stmt = $pdo->prepare("SELECT picture_number FROM picture");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['picture_number'] . '"> ' . $row['picture_number'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Tags:
					<input type="text" name="SearchTags" /></p>
		</fieldset>
		<p><input type="submit" value="Search Pictures" /></p>
	</form>
</div>
<br />







<div>
	<table>
		<tr>
			<th>Bone#</th>
			<th>Picture#</th>
			<th>Picture</th>
			<th>Tags</th>
			<th></th>
		</tr>
<?php
$qry = "SELECT bone.bone_number, picture.picture_id, picture.picture_number, picture.picture_tags FROM picture JOIN bone_picture ON picture.picture_id = bone_picture.picture_id JOIN bone ON bone.bone_id = bone_picture.bone_id WHERE bone.bone_id IS NOT NULL ";


if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['PictureNumber']))){
	$qry .= "AND picture.picture_number = :pictureNumber ";
}
if(!(empty($_POST['SearchTags']))){
	$qry .= "AND MATCH picture.picture_tags AGAINST (:searchTags) ";
}


$stmt = $pdo->prepare($qry);


if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['PictureNumber']))){
	$stmt->bindParam(':pictureNumber', $_POST['PictureNumber']);
}
if(!(empty($_POST['SearchTags']))){
	$stmt->bindParam(':searchTags', $_POST['SearchTags']);
}

$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$tags = nl2br($row['picture_tags']);
	echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['picture_number'] . "\n</td>\n<td>\n" . "<img id='" . $row['picture_number'] . "' src='test/" . $row['picture_number'] . ".JPG' height='346px' width='461px'>" . "\n</td>\n<td>\n" . $tags . "\n</td>\n<td>\n";
	echo "<button class='btn'>Edit Tags</button>
									<div class='modal'>
									<form method='post' action='view_picture.php'>
										<div class='modal-content'>
											<input type='hidden' name='PictureID' value='". $row['picture_id'] ."'>" .
											"<span class='close'>&times;</span>				
													<textarea name='PictureTags' class='boxsizingBorder' rows='10'>" . $row['picture_tags'] . "</textarea>
											<p><input type='submit' value='Save' /></p>
										</div>
									</form>
									</div>";
	echo "\n</td>\n</tr>";
}

?>


<script>
// Get the modal
var modal = document.getElementsByClassName('modal');

// Get the button that opens the modal
var btn = document.getElementsByClassName('btn');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName('close');



for(var i = 0; i < modal.length; i++){
	//add closure
	(function(_i){
		//bind button to its div contents
		var curMod = modal.item(i);
		var curBut = btn.item(i);
		var curSpan = span.item(i);

		curBut.addEventListener('click', function(){
			curMod.style.display = "block";
		});

		curSpan.addEventListener('click', function(){
			curMod.style.display = "none";
		});
	})(i);
}
</script>


	</table>
</div>
<br />










	</body>
</html>