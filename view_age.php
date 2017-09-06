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
	if( (!(empty($_POST['AgeNoteID']))) && (!(empty($_POST['AgeNotes']))) ){
		$updateNoteQry = "UPDATE bone_age SET age_notes = :ageNotes WHERE join_id = :joinID";
		$updateNoteStmt = $pdo->prepare($updateNoteQry);
		$updateNoteStmt->bindParam(':joinID', $_POST['AgeNoteID']);
		$updateNoteStmt->bindParam(':ageNotes', $_POST['AgeNotes']);
		$updateNoteStmt->execute();
	}
?>

<h3>Search Age</h3>
<div>
	<form method="post" action="view_age.php">
		<fieldset>
			<legend>Age Information</legend>
				<p>Bone Number: 
					<select name="BoneNumber">
						<option value=0>All Bones</option>
						<?php
						$stmt = $pdo->prepare("SELECT bone_number FROM bone JOIN bone_age ON bone.bone_id = bone_age.bone_id WHERE bone.bone_id IN (SELECT bone_id FROM bone_age) GROUP BY bone.bone_id ORDER BY bone_number");
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
						$stmt = $pdo->prepare("SELECT bone_type FROM type JOIN bone_type ON type.type_id = bone_type.type_id JOIN bone_age ON bone_age.bone_id = bone_type.bone_id WHERE bone_type.bone_id IN (SELECT bone_id FROM bone_age) GROUP BY bone_type");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Bone Side: 
					<select name="BoneSide">
						<option value=0>All Sides</option>
						<option value='Left'>Left</option>
						<option value='Right'>Right</option>
					</select></p>
				<p>Age Type:
					<select name="AgeType">
						<option value=0>All Types</option>
						<?php
						$stmt = $pdo->prepare("SELECT age_type FROM age GROUP BY age_type");
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
						$stmt = $pdo->prepare("SELECT age_range FROM age GROUP BY age_range");
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
			<th>BType</th>
			<th>Side</th>
			<th>AType</th>
			<th>Range</th>
			<th>Age Notes</th>
			<th></th>
		</tr>
<?php
$qry = "SELECT bone.bone_number, type.bone_type, bone.side, age.age_type, age.age_range, bone_age.age_notes, bone_age.join_id FROM bone_age JOIN bone ON bone.bone_id = bone_age.bone_id JOIN age ON age.age_id = bone_age.age_id JOIN bone_type ON bone_type.bone_id = bone.bone_id JOIN type ON type.type_id = bone_type.type_id WHERE bone.bone_id IS NOT NULL ";


if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND type.bone_type = :boneType ";
}
if(!(empty($_POST['BoneSide']))){
	$qry .= "AND bone.side = :boneSide ";
}
if(!(empty($_POST['AgeType']))){
	$qry .= "AND age.age_type = :ageType ";
}
if(!(empty($_POST['AgeRange']))){
	$qry .= "AND age.age_range = :ageRange ";
}
$qry .= "ORDER BY bone_number ";
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
if(!(empty($_POST['AgeType']))){
	$stmt->bindParam(':ageType', $_POST['AgeType']);
}
if(!(empty($_POST['AgeRange']))){
	$stmt->bindParam(':ageRange', $_POST['AgeRange']);
}



$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$notes = nl2br($row['age_notes']);

	echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['bone_type'] . "\n</td>\n<td>\n" . $row['side'] . "\n</td>\n<td>\n" . $row['age_type'] . "\n</td>\n<td>\n" . $row['age_range'] . "\n</td>\n<td>\n" . $notes . "\n</td>\n<td>\n";
		echo "<button class='btn'>Edit Notes</button>
						<div class='modal'>
						<form method='post' action='view_age.php'>
							<div class='modal-content'>
								<input type='hidden' name='AgeNoteID' value='". $row['join_id'] ."'>" .
								"<span class='close'>&times;</span>				
										<textarea name='AgeNotes' class='boxsizingBorder' rows='10'>" . $row['age_notes'] . "</textarea>
								<p><input type='submit' value='Save' /></p>
							</div>
						</form>
						</div>";
	echo "\n</td>\n</tr>";
}

?>
	</table>
</div>
<br />


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









	</body>
</html>