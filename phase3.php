<html>
	<head>
	</head>
	<body>
		<form action="" method="post">
			<label for="Character ID">ID</label>
			<input type="number" name="character_id" id="character_id">
				<br/>

			<label for="search">search</label>
			<input type="submit" name="search" id="search">
				<br/>

		</form>
	</body>
</html> 

<?php
	
	$student_id="";

	if($_SERVER["REQUEST_METHOD"]== "POST") {
		$conn = new mysqli('localhost', 'root', '', 'charactermanager');

		if ($conn->connect_error) {
			echo "connection failed";
			die("Connection failed: " . $conn->connect_error);
		}
		
		

		$character_id = $_POST['character_id'];
		
		// Query 1
		$resultSet = $conn->query("select * from game_character where id = '$character_id'");
		if ($resultSet->num_rows != 0) {
			echo "<b>Character Info:</b>";
			while ($rows = $resultSet->fetch_assoc()) {
				$character_id = $rows['id'];
				$char_name= $rows['char_name'];
				$description = $rows['description'];
				echo "<p>ID: $character_id <br/>Name: $char_name<br/>Description: $description<br/></p>";
			}
		
			// Query 2
			$resultSet = $conn->query("select * from item where char_id = '$character_id'");
			if ($resultSet->num_rows != 0) {
				echo "<b><br/>Character Items:</b>";
				while ($rows = $resultSet->fetch_assoc()) {
					$item_name = $rows['item_name'];
					$weight= $rows['weight'];
					$description = $rows['description'];
					echo "<p>Name: $item_name<br/>Weight: $weight<br/>Description: $description<br/></p>";
				}
			} else {
				echo "<b><br/>Character has no items.</b>";
			}
			
			// Query 3
			$resultSet = $conn->query("select * from char_spell inner join spell on char_spell.spell_id = spell.id where char_id = '$character_id'");
			if ($resultSet->num_rows != 0) {
				echo "<b><br/>Known Spells:</b>";
				while ($rows = $resultSet->fetch_assoc()) {
					$spell_name = $rows['spell_name'];
					$description = $rows['description'];
					echo "<p>Name: $spell_name<br/>Description: $description<br/></p>";
				}
			} else {
				echo "<b><br/>Character does not know any spells.</b>";
			}
			
			// Query 4
			$resultSet = $conn->query("select * from char_party inner join party on char_party.party_id = party.id where char_id = '$character_id'");
			if ($resultSet->num_rows != 0) {
				echo "<b><br/>Character Party:</b>";
				
				$rows = $resultSet->fetch_assoc();
				$party_name = $rows['party_name'];
				$color = $rows['color'];
				echo "<p>Name: $party_name<br/>HexColor: $color<br/></p>";
				
				echo "<b><br/>All characters in party:</b>";
				$party_id = $rows['party_id'];
				$resultSet = $conn->query("select * from game_character inner join char_party on game_character.id = char_party.char_id where party_id = '$party_id'");
				$counter = 1;
				while ($rows = $resultSet->fetch_assoc()) {
					$char_name= $rows['char_name'];
					echo "<p>$counter: $char_name</p>";
					$counter += 1;
				}
			}
			
			// Query 5
			$resultSet = $conn->query("select * from association inner join organization on association.org_id = organization.id where party_id = '$party_id'");
			if ($resultSet->num_rows != 0) {
				echo "<b><br/>Party Organization Relationships:</b>";
				while ($rows = $resultSet->fetch_assoc()) {
					$org_name = $rows['org_name'];
					$description = $rows['description'];
					$relationship = $rows['relation'];
					echo "<p>Name: $org_name<br/>Description: $description<br/>Relationship: $relationship<br/></p>";
				}
			} else {
				echo "<b><br/>Character does not know any spells.</b>";
			}

			
			

		} else {
			echo "<b>Character with that id does not exist.</b>";
		}
	} else{
		echo "search didnt get hit";
	}

	?>