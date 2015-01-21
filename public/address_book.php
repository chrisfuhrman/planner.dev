<?php

require_once '../inc/filestore.php';

class AddressDataStore extends Filestore
{

	function __construct($filename) {
		parent::__construct(strtolower($filename));
	}

}

	$address_obj = new AddressDataStore('address_book.csv');
	$address_obj->contents = $address_obj->read();


// Verify there were uploaded files and no errors
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
	// Set the destination directory for uploads
	$uploadDir = '/vagrant/sites/planner.dev/public/uploads/';

	// Grab the filename from the uploaded file by using basename
	$uploadedFile = basename($_FILES['file1']['name']);

	// Create the saved filename using the file's original name and our upload directory
	$savedFilename = $uploadDir . $uploadedFile;


	$address_obj_uploaded = new AddressDataStore($savedFilename);

	$address_obj_uploaded->read();

	// Move the file from the temp location to our uploads directory
	move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);

	
 
	$address_obj->contents = array_merge($address_obj->contents, $address_obj_uploaded->read());
	$address_obj->write();
}


// function to Save new stuff to csv
if (!empty($_POST)) {

	$error = false;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = strip_tags($value);


		if (empty($value)) {
			$error = true;
		}
	}

	if ($error) {
		$message = 'Please fill out all the fields';
	} else {
		array_push($address_obj->contents, $_POST);
		$address_obj->write();	
	}
}



if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($address_obj->contents[$id]);
}

$address_obj->write();



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Address Book</title>

	<!-- Bootstrap -->
	<link rel="icon" href="/favicon.ico">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css"> 
	<!-- <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">  -->
	<link rel="stylesheet" type="text/css" href="css/rangeslider.css"> 
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
</head>

	<body>

	<style>

		body {
			background-color: antiquewhite;
		}
		#headline {
			color: rgba(73, 181, 113, 1);
			font-size: 3em;
			font-family: 'Oxygen', sans-serif;
			text-align: center;
		}
		#delete-btn {
			background-color: none;
			text-align: center;
			border: none;
			font-weight: bold;
			font-size: 1.2em;
		}
		#delete-btn a {
			color: red;
		}
		table, tr, th, td {
			border: 2px solid black;
		}
		th {
			text-align: center;
			font-weight: bold;
			color: white;
			background-color: rgba(66, 142, 255, 1);
		}
		td {
			text-align: left;

		}
		table {
			width: 80%;
			margin: 25px auto 25px;
		}

		#footer {
			background-color: gray;
			text-align: center;
			color: #fff;
		}

	</style>

		<div class="container">
		<!-- Display table -->
			<h1 id="headline">Address Book</h1>
			<table>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>City</th>
					<th>State</th>
					<th>Zip Code</th>
					<!-- <th>Remove</th> -->
				</tr>

				<? foreach ($address_obj->contents as $key => $row): ?>
					<tr>
					<?foreach ($row as $value): ?>
						<td colspan="1"> <?= $value ?></td>
					<? endforeach; ?>
				<td id="delete-btn">
					<a href="?remove=<?= $key; ?>">Delete</a>
				</td>

				</tr>

				<? endforeach; ?>
					
			</table>



			<? if (isset($message)): ?>
				<div class="alert alert-danger"><?= $message ?></div>
			<? endif ?>

			<!-- form to enter a new contact -->
			<div class="container">
				<!-- <div class="row"> -->
				<h2 class="form_title">Add a contact to address book</h2>
				<form method="POST" action="/address_book.php">

					<div class="form-group">
						<div class="row">
							<div class="input-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" name="name" id="name">
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="row">
							<div class="input-group">
							<label for="address">Address:</label>
							<input type="text" class="form-control" name="address" id="address">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="input-group">
							<label for="city">City:</label>
							<input type="text" class="form-control" name="city" id="city">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="input-group">
							<label for="state">State:</label>
							<input type="text" class="form-control" name="state" id="state">
							</div>
						</div>

					</div>
					<div class="form-group">
						<div class="row">
							<div class="input-group">
							<label for="zip">Zip:</label>
							<input type="text" class="form-control" name="zip" id="zip">
							</div>
						</div>
					</div>

					<div class="form-group btn-width">
						<button type="submit">Add</button>
					</div>

				</form>
			</div>

		<!-- Upload File form -->
			<div class="col-md-3">
				<h2>Upload File</h2>

				<form method="POST" enctype="multipart/form-data" action="/contents.php">
					<p>
						<label for="file1">File to upload: </label>
						<input type="file" id="file1" name="file1">
					</p>
					<p>
						<input type="submit" value="Upload">
					</p>
				</form>
			</div>

		</div>


<!-- Sub-Footer -->
		<section id="footer">
			<div class="container">
				<div class="row">
					<p>&copy; Chris Fuhrman</p>
					<p>Address Book<p>
				</div>
			</div>
		</section>


		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

		<script>
			
			var rows = document.getElementsByTagName('tr');

			// var liToSalmonColor = function(e) {
				for (i = 1; i < rows.length; i++) {
					if (i % 2 == 0) {
						rows[i].style['background-color'] = 'rgba(15, 219, 253, 1)';
					}
				}
		 

		</script>

	</body>
</html>


