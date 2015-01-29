<?php

require_once 'inc/config.php';
require_once 'inc/address.class.php';

$addressObj = new Address($dbc);

// Redirect to index if GET and POST are both empty.
if (empty($_GET['id']) AND empty($_POST)) {

	header('Location: index.php');
}

// If form was submitted, update the address record.
if (!empty($_GET)) {

	$addressId = $_GET['id'];

	$addressObj->load($addressId);

}
$error = false;

if (!empty($_POST)) {
	$addressId = $_POST['id'];
	$addressObj->load($addressId);
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

				$addressObj->id = $_POST['id'];

				$addressObj->address = $_POST['address'];
				$addressObj->city = $_POST['city'];
				$addressObj->state = $_POST['state'];
				$addressObj->zip = $_POST['zip'];
				$phone = $_POST['phone'];
				if(strlen($phone) == 10){
					$cleanPhone = preg_replace("/(\d{3})(\d{3})(\d{4})/", "$1-$2-$3", $phone);
					$addressObj->phone = $cleanPhone;	
				}
			// function to save to db
			$addressObj->save();

			$message = "Address successfully updated!";

			header('Location: index.php');

			}else{
				throw new Exception("Your Address is too long");
				header('Location: edit_address.php?id=' . $addressId);
			}


		} else {
			throw new Exception('Please fill out all fields');
			header('Location: edit_address.php?id=' . $addressId);

		}

	} catch (Exception $e) {
		$message = $e->getMessage();
	}
} else{
	$id = $_GET['id'];
}

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
					<input class="form-control" type="text" name="address" value="<?= $addressObj->address ?>" size="45" placeholder="Address: 123 Somewhere St"></input>

					<label class="labelType">City:</label>
					<input class="form-control" type="text" name="city" value="<?= $addressObj->city ?>" size="45" placeholder="City"></input>

					<label class="labelType">State:</label>
					<select class="form-control" name="state" style="width: 250px" placeholder="State">
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
					<input class="form-control" type="text" name="zip" value="<?= $addressObj->zip ?>" size="45" placeholder="Zip: 12345"></input>

					<label class="labelType">Phone Number:</label>
					<input class="form-control" type="text" name="phone" value="<?= $addressObj->phone ?>" size="45" placeholder="Phone: 1234567890"></input>

				<input class="btn btn-default" type="submit">
				<a class="btn btn-default" href="index.php">Back</a>
			</form>
		</div>
	</div>
</div>
	
</body>
</html>