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
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
<?php

$filename = 'address_book.csv';

// Function for reading CSV
function openCSV($filename) {

	$handle = fopen($filename, 'r');

	$address_book = [];

	while(!feof($handle)) {
		$row = fgetcsv($handle);

		if (!empty($row)) {
			$address_book[] = $row;
		}
	}
	fclose($handle);
	return $address_book;
}

// php function to write to CSV file
function saveAddressBook($filename, $address_book) {
	$handle = fopen($filename, 'w');

	foreach ($address_book as $row) {
		fputcsv($handle, $row);	
	}

	fclose($handle);
}



$address_book = openCSV($filename);

// function to write to csv
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
		array_push($address_book, $_POST);
		saveAddressBook($filename, $address_book);
	}
}



if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($address_book[$id]);
}

saveAddressBook($filename, $address_book);

?>

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
		/*	.form-group {
				margin: 50px;
			}*/
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

				<? foreach ($address_book as $key => $row): ?>
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










