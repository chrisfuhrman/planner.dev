<?php

require_once 'inc/config.php';
require_once 'inc/person.class.php';

$personObj = new Person($dbc);

// Redirect to index if GET and POST are both empty.
if (empty($_GET['id']) && empty($_POST)) {

	header('Location: index.php');
}

// If form was submitted, update the person record.
if (!empty($_POST)) {

	$error = false;

	foreach ($_POST as $key => $value) {
	$_POST[$key] = strip_tags($value);

		// verify all fields were filled out
		if (empty($value)) {
			$error = true;
		}
	}

	try {
		if (!$error) {

			$personObj->id = $_POST['id'];

			$personObj->first_name = $_POST['first_name'];
			$personObj->last_name = $_POST['last_name'];

			// function to save to db
			$personObj->save();

			$message = "Person successfully updated!";

			header('Location: index.php');

	} else {
		throw new Exception('Please fill out all fields');
	}

	} catch (Exception $e) {
		$message = $e->getMessage();
	}
} else {
	// set the id from GET
	$id = $_GET['id'];
}

// load data from db
$personObj->load($id);

?>

<!DOCTYPE html>
<html lang="en">

	<title>Edit Person Information</title>
	<? require_once 'inc/header.php'; ?>


<body>

	<? if (isset($message)): ?>
		<div class="alert alert-danger"><?= $message ?></div>
	<? endif ?>


	<h2 class="editHeader">Edit Person</h2>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<form class="form-group" action="edit_person.php" method="post">
						<input type="hidden" name="id" value="<?=$personObj->id?>">

						<label class="labelType">First Name:</label>
						<input class="form-control" type="text" name="first_name" value="<?= $personObj->first_name ?>">
						
						<label class="labelType">Last Name:</label>
						<input class="form-control" type="text" name="last_name" value="<?= $personObj->last_name ?>"></input>
						<input class="btn btn-default" type="submit"></input>
				</form>
			</div>
		</div>
	</div>

</body>
</html>