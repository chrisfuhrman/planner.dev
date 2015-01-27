<?php

require_once 'inc/config.php';

// include person.class file
require_once 'inc/person.class.php';


// Initialize class to test

if (!empty($_POST)) {
	$personObject = new Person($dbc);

	$personObject->first_name = $_POST['first_name'];
	$personObject->last_name  = $_POST['last_name'];

	$personObject->save();


}






?>

<!-- Header file -->
<? require_once 'inc/header.php'; ?>

	<h1>TEST FILE</h1>

<!-- form to enter a new person -->
	<div class="container">
		<form class="form-inline" id="item-form" method="POST" action="test.php">
		<h1>Add a person</h1>
		<? require_once 'templates/person.form.php'; ?>
		</form>
	</div>

	</body>
	</html>