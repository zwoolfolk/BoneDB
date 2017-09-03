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
	if( (!(empty($_POST['JoinID']))) && (!(empty($_POST['IndivNotes']))) ){
		$updateNoteQry = "UPDATE bone_individual SET individual_notes = :indivNotes WHERE join_id = :joinID";
		$updateNoteStmt = $pdo->prepare($updateNoteQry);
		$updateNoteStmt->bindParam(':joinID', $_POST['JoinID']);
		$updateNoteStmt->bindParam(':indivNotes', $_POST['IndivNotes']);
		$updateNoteStmt->execute();
	}
?>

<h3>Search Individuals</h3>
<div>
	<form method="post" action="view_individual.php">
		<fieldset>
			<legend>Individual Information</legend>
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
				<p>Individual Number:
					<select name="IndividualNumber">
						<option value=0>All Individuals</option>
						<?php
						$stmt = $pdo->prepare("SELECT individual_id FROM individual");
						$stmt->execute();
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<option value="'. $row['individual_id'] . '"> ' . $row['individual_id'] . '</option>\n';
						}
						?>
					</select></p>
		</fieldset>
		<p><input type="submit" value="Search Individuals" /></p>
	</form>
</div>
<br />







<div>
	<table>
		<tr>
			<th>Bone#</th>
			<th>Individual#</th>
			<th>Individual Notes</th>
			<th></th>
		</tr>
<?php
$qry = "SELECT bone.bone_number, individual.individual_id, bone_individual.individual_notes, bone_individual.join_id FROM bone JOIN bone_individual ON bone_individual.bone_id = bone.bone_id JOIN individual ON individual.individual_id = bone_individual.individual_id WHERE bone.bone_id IS NOT NULL ";

if(!(empty($_POST['BoneNumber']))){
	$qry .= "AND bone.bone_number = :boneNumber ";
}
if(!(empty($_POST['IndividualNumber']))){
	$qry .= "AND individual.individual_id = :indivNumber ";
}



$stmt = $pdo->prepare($qry);


if(!(empty($_POST['BoneNumber']))){
	$stmt->bindParam(':boneNumber', $_POST['BoneNumber']);
}
if(!(empty($_POST['IndividualNumber']))){
	$stmt->bindParam(':indivNumber', $_POST['IndividualNumber']);
}


$stmt->execute();

echo "<div>" . $stmt->rowCount() . " records found.</div><br />";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$notes = nl2br($row['individual_notes']);


	echo "<tr>\n<td>\n" . $row['bone_number'] . "\n</td>\n<td>\n" . $row['individual_id'] . "\n</td>\n<td>\n" . $notes . "\n</td>\n<td>\n";
		echo "<button class='btn'>Edit Notes</button>
								<div class='modal'>
								<form method='post' action='view_individual.php'>
									<div class='modal-content'>
										<input type='hidden' name='JoinID' value='". $row['join_id'] ."'>" .
										"<span class='close'>&times;</span>				
												<textarea name='IndivNotes' class='boxsizingBorder' rows='10'>" . $row['individual_notes'] . "</textarea>
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