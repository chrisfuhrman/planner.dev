<?php

require_once 'inc/config.php';
require_once 'inc/address.class.php';
require_once 'inc/person.class.php';

$personObj = new Person($dbc);
$addressObj = new Address($dbc);


if (empty($_GET['id']) && empty($_POST)) {

	header('Location: index.php');
}

// Page is called, but form is not yet submitted.
// Use $_GET to determine who this address is for.
if (!empty($_GET)) {

	$personId = $_GET['id'];

	$personObj->load($personId);

}
$error = false;

// If form was submitted, insert into db.
if (!empty($_POST)) {

	$personId = $_POST['person_id'];
	$personObj->load($personId);
	$errorStatus = $addressObj->checkInput($_POST);


	foreach ($_POST as $key => $value) {

		$_POST[$key] = strip_tags($value);

		// verify all fields were filled out
		if (empty($value) && $key != 'phone') {

			$error = true;
		}
	}

	try {
		if (!$error) {

			if (!$errorStatus){

				$addressObj->address = $_POST['address'];
				$addressObj->city = $_POST['city'];
				$addressObj->state = $_POST['state'];
				$addressObj->zip = $_POST['zip'];
				$addressObj->person_id = $_POST['person_id'];
				$phone = $_POST['phone'];
				if(strlen($phone) == 10){
					$cleanPhone = preg_replace("/(\d{3})(\d{3})(\d{4})/", "$1-$2-$3", $phone);
					$addressObj->phone = $cleanPhone;
				}


				// function to save to db
			$addressObj->insert();

			$message = "Address successfully added!";

			header('Location: index.php');
			}else{
				throw new Exception("Your Address is too long");
				header('Location: add_address.php?id=' . $personId);
			}

		} else {
			throw new Exception('Please fill out all fields');
			header('Location: add_address.php?id=' . $personId);
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
						<input class="form-control" type="text" name="address" placeholder="Address: 123 Somewhere St"></input>
						<label class="labelType">City:</label>
						<input class="form-control" type="text" name="city" placeholder="City: Some City"></input>
						<label class="labelType">State:</label>
							<select class="form-control" name="state"></input>
								<option value="">Select a State</option>
								<? foreach ($statesArray as $abbr => $state) : ?>
									<option value="<?= $abbr ?>"><?= $state ?></option>
								<? endforeach ?>
							</select>
							
						<label class="labelType">Zip:</label>
						<input class="form-control" type="text" name="zip" placeholder="Zip: 12345"></input>
						<label class="labelType">Phone Number:</label>
						<input class="form-control" type="text" name="phone" placeholder="Phone: 1234567890"></input>
					<input class="btn btn-default" type="submit">
					<a class="btn btn-default" href="index.php">Back</a>
				</form>
			</div>
		</div>
	</div>

</body>
</html>