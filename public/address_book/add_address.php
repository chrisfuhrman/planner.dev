<?php

require_once 'inc/config.php';
require_once 'inc/address.class.php';
require_once 'inc/person.class.php';

$addressObj = new Address($dbc);
$personObj = new Person($dbc);

// var_dump($personObj->id);

// $personObj->first_name = $_POST[''];


// Redirect if nothing is passed to the form.
if (empty($_GET['id']) && empty($_POST)) {

	header('Location: index.php');
}

// Page is called, but form is not yet submitted.
// Use $_GET to determine who this address is for.
if (!empty($_GET)) {

	$personId = $_GET['id'];

	$personObj->load($personId);

}

// If form was submitted, insert into db.
if (!empty($_POST)) {


	var_dump($_POST);


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

			// $addressObj->id = $_GET['id'];

			// $addressObj->person_id = $POST['id'];
			$addressObj->address = $_POST['address'];
			$addressObj->city = $_POST['city'];
			$addressObj->state = $_POST['state'];
			$addressObj->zip = $_POST['zip'];
			$addressObj->person_id = $_POST['person_id'];

			// function to save to db
			$addressObj->insert();

			$message = "Address successfully added!";

			header('Location: index.php');

	} else {
		throw new Exception('Please fill out all fields');
	}

	} catch (Exception $e) {
		$message = $e->getMessage();
	}
} 

?>

<? require_once 'inc/header.php'; ?>


	<!-- Error Message Display Div & Logic -->
	<? if (isset($message)): ?>
		<div class="alert alert-danger"><?= $message ?></div>
	<? endif ?>

	<h2 class="editHeader">New Address for <?= $personObj->first_name . " " . $personObj->last_name ?></h2>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<form class="form-group" action="add_address.php" method="post">
					<input type="hidden" name="person_id" value="<?= $personObj->id ?>">
	
						<label class="labelType">Address:</label>
						<input class="form-control" type="text" name="address"></input>
						<label class="labelType">City:</label>
						<input class="form-control" type="text" name="city"></input>
						<label class="labelType">State:</label>
							<select class="form-control" name="state"></input>
								<option value="">Select a State</option>
								<? foreach ($statesArray as $abbr => $state) : ?>
									<option value="<?= $abbr ?>"><?= $state ?></option>
								<? endforeach ?>
							</select>
							
						<label class="labelType">Zip:</label>
						<input class="form-control" type="text" name="zip"></input>
					<input class="btn btn-default" type="submit">
				</form>
			</div>
		</div>
	</div>

</body>
</html>