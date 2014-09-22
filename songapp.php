<?php

$db = new PDO('mysql:host=localhost;dbname=php-mvc', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

if(isset($_GET['actie'])){
	$actie = $_GET['actie'];
} else {
	$actie = null;
}

switch ($actie) {
	case 'toevoegen':
		
		if(isset($_POST['submit'])){

			$sql = "INSERT INTO song (artist, track, link) VALUES (:artist, :track, :link)";

			$stmt = $db->prepare($sql);

			$stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
			$stmt->bindParam(':track', $track, PDO::PARAM_STR);
			$stmt->bindParam(':link', $link, PDO::PARAM_STR);

			$artist = $_POST['artist'];
			$track = $_POST['track'];
			$link = $_POST['link'];

			$stmt->execute();

			header('location:songapp.php');
		} else {
		?>
		<html>
			<head>
				<title>Song app</title>
				<meta charset="utf-8">

				<style>
					label{
						width: 5em;
						float: left;
					}

				</style>
				</head>
				<body>
					<h1>Toevoegen</h1>
					<form action="songapp.php?actie=toevoegen" method="POST">

						<label for="artist">Artist:</label>
						<input type="text" name="artist" id="artist"><br>

						<label for="track">Track:</label>
						<input type="text" name="track" id="track"><br>

						<label for="link">Link:</label>
						<input type="text" name="link" id="link"><br>

						<input type="submit" name="submit">

					</form>
		<?php
		}

		break;

	case 'wijzigen':

		if(isset($_POST['submit'])){

			$sql = "UPDATE song SET artist=:artist, track=:track, link=:link WHERE id=:id";

			$stmt = $db->prepare($sql);

			$stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
			$stmt->bindParam(':track', $track, PDO::PARAM_STR);
			$stmt->bindParam(':link', $link, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);

			$artist = $_POST['artist'];
			$track = $_POST['track'];
			$link = $_POST['link'];
			$id = $_POST['id'];

			$stmt->execute();

			header('location:songapp.php');
			

		} elseif (isset($_GET['id'])) {

			$sql = "SELECT * FROM song WHERE id=:id";

			$stmt = $db->prepare($sql);

			$stmt->bindParam(':id', $id, PDO::PARAM_INT);

			$id = $_GET['id'];

			$stmt->execute();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				?>
				<html>
						<head>
							<title>Song app</title>
							<meta charset="utf-8">

							<style>
								label{
									width: 5em;
									float: left;
								}

							</style>
							</head>
							<body>
								<h1>Wijzigen</h1>
								<form action="songapp.php?actie=wijzigen" method="POST">

									<label for="artist">Artist:</label>
									<input type="text" name="artist" id="artist" value="<?php echo $row['artist'] ?>"><br>

									<label for="track">Track:</label>
									<input type="text" name="track" id="track" value="<?php echo $row['track'] ?>"><br>

									<label for="link">Link:</label>
									<input type="text" name="link" id="link" value="<?php echo $row['link'] ?>"><br>

									<input type="hidden" name="id" value="<?php echo $row['id'] ?>">

									<input type="submit" name="submit">

								</form>
				<?php
			}


		} else {
			header('location:songapp.php');
		}

		break;

	case 'verwijderen':
		if(isset($_POST['ja'])){

			$sql = "DELETE FROM song WHERE id=".$_POST['id'];

			$db->exec($sql);

			header('location:songapp.php');

		} elseif(isset($_POST['nee'])) {
			header('location:songapp.php');
		} elseif (isset($_GET['id'])) {

			$sql = "SELECT * FROM song WHERE id=:id";

			$stmt = $db->prepare($sql);

			$stmt->bindParam(':id', $id, PDO::PARAM_INT);

			$id = $_GET['id'];

			$stmt->execute();

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				?>
				<html>
						<head>
							<title>Song app</title>
							<meta charset="utf-8">

							<style>
								label{
									width: 5em;
									float: left;
								}

							</style>
							</head>
							<body>
								<h1>Verwijderen</h1>
								<p>Weet je zeker dat je dit nummer wilt verwijderen?<p>
								<form action="songapp.php?actie=verwijderen" method="POST">

									<label for="artist">Artist:</label>
									<input type="text" name="artist" id="artist" value="<?php echo $row['artist'] ?>" disabled><br>

									<label for="track">Track:</label>
									<input type="text" name="track" id="track" value="<?php echo $row['track'] ?>" disabled><br>

									<label for="link">Link:</label>
									<input type="text" name="link" id="link" value="<?php echo $row['link'] ?>" disabled><br>

									<input type="hidden" name="id" value="<?php echo $row['id'] ?>">

									<input type="submit" name="ja" value="Ja">
									<input type="submit" name="nee" value="Nee">

								</form>
				<?php
			}
		} else {
			header('location: songapp.php');
		}
	
		break;

	default:
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Song app</title>
			<meta charset="utf-8">

			<style>
				label{
					width: 5em;
					float: left;
				}

			</style>
			</head>
			<body>
				<table border="1">
					<tr>
						<th>ID</th>
						<th>Artist</th>
						<th>Track</th>
						<th>Link</th>
						<th colspan="2">Actie</th>
					</tr>					
		<?php

		$sql = "SELECT * FROM song";

		$result = $db->query($sql);

		foreach ($result as $row) {
			?>
					<tr>
						<td><?php echo $row['id'] ?></td>
						<td><?php echo $row['artist'] ?></td>
						<td><?php echo $row['track'] ?></td>
						<td><a href="<?php echo $row['link'] ?>"><?php echo $row['link'] ?></a></td>
						<td><a href="songapp.php?actie=wijzigen&id=<?php echo $row['id'] ?>">Wijzigen</a></td>
						<td><a href="songapp.php?actie=verwijderen&id=<?php echo $row['id'] ?>">Verwijderen</a></td>
					</tr>
			<?php
		}

		?>
				</table>
				<a href="songapp.php?actie=toevoegen">Nieuw lied toevoegen</a>
		<?php	
		break;
}
$db = null;
?>
			</body>
		</html>