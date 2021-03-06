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
	if( (!(empty($_POST['JoinID']))) && (!(empty($_POST['AncNotes']))) ){
		$updateNoteQry = "UPDATE bone_ancestry SET ancestry_notes = :ancNotes WHERE join_id = :joinID";
		$updateNoteStmt = $pdo->prepare($updateNoteQry);
		$updateNoteStmt->bindParam(':joinID', $_POST['JoinID']);
		$updateNoteStmt->bindParam(':ancNotes', $_POST['AncNotes']);
		$updateNoteStmt->execute();
	}
?>

<h3>Search Ancestry</h3>
<div>
	<form method="post" action="view_ancestry.php">
		<fieldset>
			<legend>Ancestry Information</legend>
				<p>Bone Number: 
					<select name="BoneNumber">
						<option value=0>All Bones</option>
						<?php
						$stmt = $pdo->prepare("SELECT bone_number FROM bone JOIN bone_ancestry ON bone.bone_id = bone_ancestry.bone_id WHERE bone.bone_id IN (SELECT bone_id FROM bone_ancestry) GROUP BY bone.bone_id ORDER BY bone_number");
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
						$stmt = $pdo->prepare("SELECT bone_type FROM type JOIN bone_type ON type.type_id = bone_type.type_id JOIN bone_ancestry ON bone_ancestry.bone_id = bone_type.bone_id WHERE bone_type.bone_id IN (SELECT bone_id FROM bone_ancestry) GROUP BY bone_type");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['bone_type'] . '"> ' . $row['bone_type'] . '</option>\n';
						}
						?>
					</select></p>
				<p>Ancestry Type:
					<select name="AncestryType">
						<option value=0>All Types</option>
						<?php
						$stmt = $pdo->prepare("SELECT ancestry_type FROM ancestry");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['ancestry_type'] . '"> ' . $row['ancestry_type'] . '</option>\n';
						}
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
			<th>Bone#</th>
			<th>Bone Type</th>
			<th>Side</th>
			<th>Ancestry Type</th>
			<th>Ancestry Notes</th>
			<th></th>
		</tr>
<?php
$qry = "SELECT bone_ancestry.join_id, bone.bone_number, type.bone_type, bone.side, ancestry.ancestry_type, bone_ancestry.ancestry_notes FROM bone JOIN bone_ancestry ON bone_ancestry.bone_id = bone.bone_id JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id JOIN bone_type ON bone_type.bone_id = bone.bone_id JOIN type ON type.type_id = bone_type.type_id WHERE bone.bone_id IS NOT NULL ";

if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['BoneType']))){
	$qry .= "AND type.bone_type = :boneType ";
}
if(!(empty($_POST['AncestryType']))){
	$qry .= "AND ancestry.ancestry_type = :ancType ";
}

$qry .= "ORDER BY bone_number ";
$stmt = $pdo->prepare($qry);


if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['BoneType']))){
	$stmt->bindParam(':boneType', $_POST['BoneType']);
}
if(!(empty($_POST['AncestryType']))){
	$stmt->bindParam(':ancType', $_POST['AncestryType']);
}


$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";



while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$notes = nl2br($row['ancestry_notes']);

	 echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['bone_type'] . "\n</td>\n<td>\n" . $row['side'] . "\n</td>\n<td>\n" . $row['ancestry_type'] . "\n</td>\n<td>\n" . $notes . "\n</td>\n<td>\n";
		echo "<button class='btn'>Edit Notes</button>
						<div class='modal'>
						<form method='post' action='view_ancestry.php'>
							<div class='modal-content'>
								<input type='hidden' name='JoinID' value='". $row['join_id'] ."'>" .
								"<span class='close'>&times;</span>				
										<textarea name='AncNotes' class='boxsizingBorder' rows='10'>" . $row['ancestry_notes'] . "</textarea>
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