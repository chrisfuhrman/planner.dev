<?php

require_once 'inc/config.php';

// if (isset($_POST)) {

// 	switch ($_POST['action']) {
// 		case 'add person':
// 			$person->insert();
// 			break;
// 		case 'delete address';
// 			$address->delete();
// 			break;
// 		case $_POST['delete person']:
// 			$person->delete();
// 			break;
// 	} 
// }


// Determines the number of addresses in db
$countStmt = $dbc->query('SELECT count(*) FROM addresses');
$taskNum = $countStmt->fetchColumn();
// Amount of addresses to display per page
$limNum = 10;

// Determine page count
$pageCount = ceil($taskNum / $limNum);
// /

// Set page #'s & offset # - WE MIGHT NOT NEED THIS, DEPENDING ON THE LAYOUT
if (!isset($_GET['page'])) {
	$offsetNum = 0;
	$page = 1; 

} else {
	$page = $_GET['page'];
	$offsetNum = ($page != 1) ? ($page - 1) * $limNum: 0;
}

// BEGIN BLOCK OF CODE that IS NOT UPDATED!!!!
	// This should verify that all required fields are filled in 
	// and then save to db if entered correctly

// If post is filled out
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

			// add item to array & store to db
			$insertData = $dbc->prepare('INSERT INTO todo_list (task, due_date, category, priority) 
				VALUES (:task, :due_date, :category, :priority)
				');

		    $insertData->bindValue(':task', $_POST['task'], PDO::PARAM_STR);
		    $insertData->bindValue(':due_date', $_POST['due_date'], PDO::PARAM_STR);
		    $insertData->bindValue(':category', $_POST['category'], PDO::PARAM_STR);
		    $insertData->bindValue(':priority', $_POST['priority'], PDO::PARAM_INT);

		    $insertData->execute();

		} else {
			throw new UnexpectedTypeException('Noob, please fill out all the fields!');
		} 
	} catch (UnexpectedTypeException $e) {
		 $message = $e->getMessage();
	}
}
// END BLOCK OF CODE 



// QUERY FOR ADDRESS
$addressStmt = $dbc->prepare(
	'SELECT * FROM addresses
	 LIMIT :limNum OFFSET :offsetNum'
);

$addressStmt->bindValue(':limNum', $limNum, PDO::PARAM_INT);
$addressStmt->bindValue(':offsetNum', $offsetNum, PDO::PARAM_INT);
$addressStmt->execute();
// END QUERY FOR ADDRESS



// QUERY FOR PERSON
$personStmt = $dbc->prepare(
	'SELECT * FROM people
	 LIMIT :limNum OFFSET :offsetNum'
);

$personStmt->bindValue(':limNum', $limNum, PDO::PARAM_INT);
$personStmt->bindValue(':offsetNum', $offsetNum, PDO::PARAM_INT);
$personStmt->execute();
// END QUERY FOR PERSON


// CODE BLOCK: join Address & People Tables
$joinedQuery = 'SELECT first_name, last_name, address, city, state, zip
	FROM addresses AS a
	LEFT JOIN people AS p
	ON p.id = a.person_id';

$joinedStmt = $dbc->query($joinedQuery);



?>

<!-- header -->
<? require_once 'inc/header.php'; ?>



	<!-- Error Message Display Div & Logic -->
	<? if (isset($message)): ?>
		<div class="alert alert-danger"><?= $message ?></div>
	<? endif ?>

	<div class="container">
		<!-- Display table -->
		<h1 id="headline">Address Book</h1>
		<table>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Address</th>
				<th>City</th>
				<th>State</th>
				<th>Zip Code</th>
				<th>Remove</th>
			</tr>

			<? while ($row = $joinedStmt->fetch(PDO::FETCH_ASSOC)) : ?>
				<tr>
					<td> <?= $row['first_name']; ?> </td>
					<td> <?= $row['last_name']; ?> </td>
					<td> <?= $row['address']; ?> </td>
					<td> <?= $row['city']; ?> </td>
					<td> <?= $row['state']; ?> </td>
					<td> <?= $row['zip']; ?> </td>
					<!-- <td><a id="a-link" href="?remove=<?= $row['id'] ?>"><span id="delete-btn-1">Delete</span></a></td> -->
				</tr>
			<? endwhile; ?>
		</table>

	</div>

<!-- form to enter a new person -->
	<div class="container">
		<form class="form-inline" id="item-form" method="POST" action="test.php">
		<h1>Add a person</h1>
		<? require_once 'templates/person.form.php'; ?>
		</form>
	</div>

	
<!-- 
	two hidden form for deleteing address or deleting person
	name = 'action'
	use javascript to tie id to the forms.

	for buttons, use data-person-id -->


		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

		<script>
			
		 

		</script>

	</body>
</html>
