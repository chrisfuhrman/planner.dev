<?php

require_once 'inc/config.php';
require_once 'inc/address.class.php';

$addressObj = new Address($dbc);

// Redirect to index if GET and POST are both empty.
if (empty($_GET['id']) AND empty($_POST)) {

	header('Location: index.php');
}

// If form was submitted, update the address record.
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

			$addressObj->id = $_POST['id'];

			$addressObj->address = $_POST['address'];
			$addressObj->city = $_POST['city'];
			$addressObj->state = $_POST['state'];
			$addressObj->zip = $_POST['zip'];

			// function to save to db
			$addressObj->save();

			$message = "Address successfully updated!";

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
$address = $addressObj->load($id);

?>

<!DOCTYPE html>
<html lang="en">

	<? require_once 'inc/header.php'; ?>
	<title>Edit Address Information</title>

<body>

	<!-- Error Message Display Div & Logic -->
<? if (isset($message)): ?>
	<div class="alert alert-danger"><?= $message ?></div>
<? endif ?>

<h2 class="editHeader">Edit Address</h2>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<form class="form-group" action="edit_address.php" method="post">
				<input type="hidden" name="id" value="<?= $addressObj->id ?>">
				
					<label class="labelType">Address:</label>
					<input class="form-control" type="text" name="address" value="<?= $addressObj->address ?>" size="45"></input>

					<label class="labelType">City:</label>
					<input class="form-control" type="text" name="city" value="<?= $addressObj->city ?>" size="45"></input>

					<label class="labelType">State:</label>
					<!-- <input class="form-control" type="text" name="state" value="<?= $addressObj->state ?>" size="45"></input> -->
					<select class="form-control" name="state" style="width: 250px">
						<option value="">Select a State</option>
						<?php
						foreach ($statesArray as $abbr => $state) {
							if ($addressObj->state != $abbr) {
								echo '<option value="'.$abbr.'">'.$state.'</option>'.PHP_EOL;
							}
							else {
								echo '<option value="'.$abbr.'" selected>'.$state.'</option>'.PHP_EOL;
							}
						}
						?>
					</select>

					
					<label class="labelType">Zip:</label>
					<input class="form-control" type="text" name="zip" value="<?= $addressObj->zip ?>" size="45"></input>
					
				<input class="btn btn-default" type="submit">
			</form>
		</div>
	</div>
</div>
	
</body>
</html>