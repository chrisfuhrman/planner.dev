<?php

require_once 'inc/config.php';
require_once 'inc/person.class.php';
require_once 'inc/address.class.php';

// instantiate new Person object
$personObj = new Person($dbc);
// instantiate new Address object
$addressObj = new Address($dbc);


// CHECK IF AN ACTION WAS POSTED
if (isset($_POST['action'])) {

	switch ($_POST['action']) {
		case 'add_person':
			// set person name from POST
			try{
				if(!empty($_POST['first_name']) && !empty($_POST['last_name'])){
				
						if(strlen($_POST['first_name'] <= 20) && strlen($_POST['first_name']) <= 30) {

							$personObj->first_name = $_POST['first_name'];
							$personObj->last_name = $_POST['last_name'];

							$personObj->insert();
							break;
						}else{
							throw new Exception('Name contains more than 50 combined characters');
						}

				}else{ 
					throw new Exception('One or more fields are empty');
				}
			} catch(Exception $e){
				$message = $e->getMessage();
			}
			// method to save to db
		case 'delete_person':
			// set person id from POST
			try{
				if(isset($_POST['person_id'])){
					$personObj->id = $_POST['person_id'];

					$personObj->delete();
					break;
				}else{ 
					throw new Exception('One or more fields are empty');
				}
			} catch(Exception $e){
				$Message = $e->getMessage();
			}
		case 'delete_address';
			// set address id from POST
			try{
				if(isset($_POST['address_id'])){
					$addressObj->id = $_POST['address_id'];

					$addressObj->delete();
					break;
				}else{ 
					throw new Exception('One or more fields are empty');
				}
			} catch(Exception $e){
				$Message = $e->getMessage();
			}
	} 
}
// END ACTION POST CHECK



// BEGIN PAGINATION
if (!isset($_GET['page'])) {
	$page = 1;
	$offNum = 0;
} else {
	$page = $_GET['page'];
	$offNum = ($page - 1) * 5;
}

$limNum = 5;

$count = $dbc->query('SELECT count(*)
	FROM addresses AS a
	RIGHT JOIN people AS p
	ON p.id = a.person_id;')->fetchColumn();

$totalPages = ceil($count / 5);

$next = $page + 1;
$previous = $page - 1;
// End of PAGINATION


// CODE BLOCK: JOIN Address & People Tables
$joinedQuery = 'SELECT a.id AS address_id, p.id AS user_id, person_id, first_name, last_name, address, city, state, zip, phone
	FROM addresses AS a
	RIGHT JOIN people AS p
	ON p.id = a.person_id
	LIMIT :limNum
	OFFSET :offNum';
	$joinedStmt = $dbc->prepare($joinedQuery);

	$joinedStmt->bindValue(':limNum', $limNum, PDO::PARAM_INT);
	$joinedStmt->bindValue(':offNum', $offNum, PDO::PARAM_INT);
	$joinedStmt->execute();

?>

<!-- HEADER -->
<? require_once 'inc/header.php'; ?>

	<!-- Error Message Display Div & Logic -->
	<? if (isset($message)): ?>
		<div class="alert alert-danger"><?= $message ?></div>
	<? endif ?>

	<div class="container">
		<!-- Display table -->
		<h1 class="addressHeader">Address Book</h1>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<table class="table table-bordered" class="outer-table">
					<th>Person</th>
					<th>Address</th>
						<? while ($row = $joinedStmt->fetch(PDO::FETCH_ASSOC)) : ?>
				
							<tr>
								<td>
									<span class="name-bold"><?= $row['first_name'] . ' ' . $row['last_name'] ?></span>
									<a class="btn btn-sm btn-primary" href="edit_person.php?id=<?= $row['user_id'] ?>">Edit</a>&nbsp;
									<button type="button" class="btn btn-danger btn-sm dlt-btn-person" data-person-id="<?=$row['user_id']?>">Delete</button>
									<a class="btn btn-success btn-sm" href="add_address.php?id=<?= $row['user_id']?>">New Address</a>
								</td>

								<td>

									<table class="inner-table">
							
										<tr>
											<td>
												<span class="newLine"><?=  " " . $row['address']; ?></span>
												<span class="newLine"><?= " " . $row['city'] . " " . $row['state']; ?></span>
												<span class="newLine"><?= " " .  $row['zip']; ?></span>
												<span class="newLine"><?= " " . $row['phone']; ?></span>
											</td>
											
											<div class="right-justify">
												<a class="btn btn-primary btn-2 btn-sm" href="edit_address.php?id=<?=$row['address_id']?>">Edit</a>&nbsp;
												<button type="button" class="btn btn-danger btn-2 btn-sm dlt-btn-address" data-address-id="<?=$row['address_id']?>">Delete</button>
											</div>
											
										</tr>
									</table>

									
								</td>
							</tr>
						<? endwhile ?>
				</table>
		</div>
	</div>

<!-- BEGIN NEW PERSON FORM -->
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<form class="form-group" id="item-form" method="POST" action="index.php">
					<h2 class="addPersonHeader">Add a person</h2>
					<? require_once 'templates/person.form.php'; ?>
				</form>
			</div>
		</div>
	</div>
<!-- END NEW PERSON FORM -->

<!-- include hidden forms -->
	<? require_once 'inc/hidden_forms.php' ?>

<!-- BEGIN PAGINATION 2ND BLOCK -->
	<nav>
		<ul class="pagination">
			<li>
				<? if($page > 1): ?>
					<a href="?page=<?=$previous?>" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
				<? endif; ?>
			</li>

			<? if(($totalPages > 3) && ($page > 2)):?>
				<li>
					<a href="?page=<?=$previous-1?>"> <?=$previous-1?></a>
				</li>
			<? endif; ?>

			<? if($page > 1):?>
				<li>
					<a href="?page=<?=$previous?>"> <?=$previous?> </a>
				</li>
			<? endif; ?>
				<li class= "active">
					<a href="?page=<?=$page?>"> <?=$page?></a>
				</li>
			<? if($totalPages != $page): ?>
				<li>
					<a href="?page=<?=$next?>"> <?=$next?> </a>
				</li>
			<? endif; ?>
			<? if(($totalPages > 3) && ($totalPages >= $next+1)): ?>
				<li>
					<a href="?page=<?=$next+1?>"> <?=$next+1?></a>
				</li>
			<? endif; ?>
			<li>
			<? if($page <= ($totalPages - 1)): ?>	
				<a href="?page=<?=$next?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			<? endif; ?>
			</li>
		</ul>
	</nav>
<!-- End of Pagination -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<!-- custom script  -->
	<script src="inc/script.js"></script>	


</body>
</html>
