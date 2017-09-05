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



<h3>Add New Bone</h3>
<div>
<form method="post" action="add_bone.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: <input type="number" name="BoneNumber" /></p>
			<p>Bone Sex: 
				<select name="BoneSex">
					<option value=0>(Blank)</option>
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
			<p>Bone Side: 
				<select name="BoneSide">
					<option value=0>(Blank)</option>
					<option value='Left'>Left</option>
					<option value='Right'>Right</option>
				</select></p>
			<p>Bone Bag: 
				<select name="BoneBag">
					<?php
					$stmt = $pdo->prepare("SELECT bag_id, bag_number FROM bag");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bag_id'] . '"> ' . $row['bag_number'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Add Bone" /></p>
</form>
</div>
<br />




<h3>Update Bone</h3>
<div>
<form method="post" action="update_bone.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, sex, side FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id ORDER BY bone_number");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - '; 
						if($row['sex'] == 'NULL'){
							echo ' ' . '</option>\n';
						} else {
							echo $row['sex'];
						}
						echo ' - ' . $row['side'] . '</option>\n';
					}
					?>
				</select></p>
			<p>Bone Sex: 
				<select name="BoneSex">
					<option value=0>(Blank)</option>
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
			<p>Bone Side: 
				<select name="BoneSide">
					<option value=0>(Blank)</option>
					<option value='Left'>Left</option>
					<option value='Right'>Right</option>
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
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, ancestry.ancestry_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id LEFT JOIN bone_ancestry ON bone.bone_id = bone_ancestry.bone_id LEFT JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id ORDER BY bone_number");
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
					$stmt = $pdo->prepare("SELECT join_id, bone_number, type.bone_type, ancestry.ancestry_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id JOIN bone_ancestry ON bone.bone_id = bone_ancestry.bone_id JOIN ancestry ON ancestry.ancestry_id = bone_ancestry.ancestry_id ORDER BY bone_number");
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
				<select name="BoneNumber">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, sex FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id ORDER BY bone_number");
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
			<p>Sample Type:
				<select name="SampleType">
					<?php
					$stmt = $pdo->prepare("SELECT sample_id, sample_type FROM sample");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['sample_id'] . '"> ' . $row['sample_type'] . '</option>\n';
					}
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Add Sample" /></p>
	</form>
</div>
<br />



<h3>Remove Sample</h3>
<div>
<form method="post" action="remove_sample.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Sample Number: 
				<select name="SampleNumber">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_sample.join_id, sample.sample_type, bone_number, type.bone_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id JOIN bone_sample ON bone_sample.bone_id = bone.bone_id JOIN sample ON bone_sample.sample_id = sample.sample_id ORDER BY join_id");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['join_id'] . '"> ' . 'Sample #' . $row['join_id'] . ' - ' . $row['sample_type'] . ' - ' . $row['bone_number'] . ' - ' . $row['bone_type']; 
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Sample" /></p>
	</form>
</div>
<br />



<h3>Add Bone Age</h3>
<div>
<form method="post" action="add_age.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type, age.age_type, age.age_range FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id LEFT JOIN bone_age ON bone.bone_id = bone_age.bone_id LEFT JOIN age ON age.age_id = bone_age.age_id ORDER BY bone_number");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - ' . $row['age_type'] . ' - ' . $row['age_range'] . '</option>\n';
					} 
					?>
				</select></p>
			<p>Bone Age: 
				<select name="BoneAge">
					<?php 
					$stmt = $pdo->prepare("SELECT age_id, age_type, age_range FROM age ORDER BY age_type");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){					
						echo '<option value="'. $row['age_id'] . '"> ' . $row['age_type'] . ' - ' . $row['age_range'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Add Age" /></p>
</form>
</div>
<br />



<h3>Remove Bone Age</h3>
<div>
<form method="post" action="remove_age.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="JoinID">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_age.join_id, bone.bone_number, type.bone_type, age.age_type, age.age_range FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id JOIN bone_age ON bone.bone_id = bone_age.bone_id JOIN age ON age.age_id = bone_age.age_id ORDER BY bone_number");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['join_id'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - ' . $row['age_type'] . ' - ' . $row['age_range'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Age" /></p>
</form>
</div>
<br />



<h3>Add New Age Type</h3>
<div>
<form method="post" action="add_age_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Age Type: <input type="text" name="AgeType" /></p>
			<p>Age Range: <input type="text" name="AgeRange" /></p>
	</fieldset>
	<p><input type="submit" value="Add Age Type" /></p>
</form>
</div>
<br />



<h3>Remove Age Type</h3>
<div>
<form method="post" action="remove_age_type.php">
	<fieldset>
		<legend>Type Information</legend>
			<p>Age Type: 
				<select name="AgeID">
					<?php 
					$stmt = $pdo->prepare("SELECT age_id, age_type, age_range FROM age ORDER BY age_type");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){					
						echo '<option value="'. $row['age_id'] . '"> ' . $row['age_type'] . ' - ' . $row['age_range'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Age Type" /></p>
</form>
</div>
<br />





<h3>Add Bone to Individual</h3>
<div>
<form method="post" action="add_bone_individual.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="BoneNumber">
					<?php 
					$stmt = $pdo->prepare("SELECT bone_number, type.bone_type FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id WHERE bone.bone_id NOT IN (SELECT bone_id FROM bone_individual) ORDER BY bone_number");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['bone_number'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . '</option>\n';
					} 
					?>
				</select></p>
			<p>Individual Number:
				<select name="IndividualID">
					<?php 
					$stmt = $pdo->prepare("SELECT individual_id FROM individual");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['individual_id'] . '"> ' . $row['individual_id'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Add Bone to Individual" /></p>
</form>
</div>
<br />




<h3>Remove Bone from Individual</h3>
<div>
<form method="post" action="remove_bone_individual.php">
	<fieldset>
		<legend>Bone Information</legend>
			<p>Bone Number: 
				<select name="JoinID">
					<?php 
					$stmt = $pdo->prepare("SELECT join_id, bone_number, type.bone_type, individual.individual_id FROM bone JOIN bone_type ON bone.bone_id = bone_type.bone_id JOIN type ON type.type_id = bone_type.type_id JOIN bone_individual ON bone.bone_id = bone_individual.bone_id JOIN individual ON bone_individual.individual_id = individual.individual_id ORDER BY bone_number");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['join_id'] . '"> ' . $row['bone_number'] . ' - ' . $row['bone_type'] . ' - Individual #' . $row['individual_id'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Bone from Individual" /></p>
</form>
</div>
<br />




<h3>Add New Individual</h3>
<div>
<form method="post" action="add_individual.php">
	<fieldset>
		<legend>Individual Information</legend>
			<p>Next Individual Number: 
			<?php 
				echo "<input type='number' name='indivNumber' value='";
				$stmt = $pdo->prepare("SHOW TABLE STATUS LIKE 'individual'");
				$stmt->execute();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$nextNum = $row['Auto_increment'];
					echo $nextNum;
				}
				echo "' readonly='readonly' /></p>";
			?>
	</fieldset>
	<p><input type="submit" value="Add New Individual" /></p>
</form>
</div>
<br />

<h3>Remove Individual</h3>
<div>
<form method="post" action="remove_individual.php">
	<fieldset>
		<legend>Individual Information</legend>
			<p>Individual Number:
				<select name="IndividualID">
					<?php 
					$stmt = $pdo->prepare("SELECT individual_id FROM individual");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['individual_id'] . '"> ' . $row['individual_id'] . '</option>\n';
					} 
					?>
				</select></p>
	</fieldset>
	<p><input type="submit" value="Remove Individual" /></p>
</form>
</div>
<br />



<h3>Add Picture</h3>
<div>
<form method="post" action="add_picture.php">
	<fieldset>
		<legend>Individual Information</legend>
			<p>Individual Number:
				<select name="">
					<?php 
					$stmt = $pdo->prepare("SELECT individual_id FROM individual");
					$stmt->execute();
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo '<option value="'. $row['individual_id'] . '"> ' . $row['individual_id'] . '</option>\n';
					} 
					?>
				</select></p>
			<p><input type="file" name="pictureFile" /></p>
	</fieldset>
	<p><input type="submit" value="Remove Individual" /></p>
</form>
</div>
<br />






	</body>
</html>