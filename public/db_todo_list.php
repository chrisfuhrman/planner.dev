<?php

// Connect to db
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'todo_db');
define('DB_USER', 'codeup');
define('DB_PASS', 'codeup');
require_once '../db_connect.php';



require_once '../inc/filestore.php';
class ToDoListStore extends Filestore

{

}



// Determines the number of tasks in db
$countStmt = $dbc->query('SELECT count(*) FROM todo_list');
$taskNum = $countStmt->fetchColumn();
// Amount of tasks to display per page
$limNum = 10;

// Determine page count
$pageCount = ceil($taskNum / $limNum);




if (!isset($_GET['page'])) {
	$offsetNum = 0;
	$page = 1; 

} else {
	$page = $_GET['page'];
	$offsetNum = ($page != 1) ? ($page - 1) * $limNum: 0;
}




try {

	if (isset($_POST['item'])) {
		
		if (empty($_POST['item']) || strlen($_POST['item']) > 255) {
			throw new Exception('You failed, enter a real task!');
		}

		// add item to array & store to db
		$tasks[] = htmlspecialchars(strip_tags($_POST['item']));

		$insertData = $dbc->prepare('INSERT INTO todo_list (task) VALUES (:task)');


		foreach ($tasks as $task) {
		    $insertData->bindValue(':task', $task, PDO::PARAM_STR);

		    $insertData->execute();
		}
	}

} catch (Exception $e) {
	$message = $e->getMessage();
}



if (isset($_GET['remove'])) {
	$id = $_GET['remove'];

	$deleteData = $dbc->prepare('DELETE FROM todo_list WHERE id = :id');
	$deleteData->bindValue(':id', $id, PDO::PARAM_INT);

	$deleteData->execute();

}

// Query db with dynamic limit and offset
$stmt = $dbc->prepare(
	'SELECT * FROM todo_list
	ORDER BY due_date DESC;
	 LIMIT :limNum OFFSET :offsetNum'
);

$stmt->bindValue(':limNum', $limNum, PDO::PARAM_INT);
$stmt->bindValue(':offsetNum', $offsetNum, PDO::PARAM_INT);

$stmt->execute();


?>


<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/todo.css">
</head>
<body>
	<div id="intro-content">
		<!-- Error Message -->
		<? if (isset($message)): ?>
		<div class="alert alert-danger"><?= $message ?></div>
		<? endif ?>

	<!-- form to enter a new todo item -->
		<div class="container">
		<form class="form-inline" id="item-form" method="POST" action="/db_todo_list.php">

			<div class="form-group">
				<label class="sr-only" for="task">Task</label>
				<input type="text" class="form-control"  id="task" placeholder="task" name="task"></label>
			</div>

			<div class="form-group">
				<label class="sr-only" for="due_date">Due Date</label>
				<input name="due_date" type="datetime" placeholder="due date" class="form-control"  id="due_date">
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
  			</div>	

  			<div class="form-group">
				<label class="sr-only" for="category">Category</label>
				<select class="form-control">
					<option>egm</option>
					<option>business</option>
					<option>personal</option>
				</select>
			</div>

  			<div class="form-group">
				<label class="sr-only" for="priority">priority</label>
				<select class="form-control" name="priority">
					<option value="3">high</option>
					<option value="2">middle</option>
					<option value="1">low</option>
				</select>
			</div>

			<button id="add-btn" type="submit" class="btn btn-default">Add</button>
			
		</form>
		</div>





		<!-- List -->
		<div id="list-section">
			<div id="task-title-div"><h2 id="list-title">My Tasks</h2></div>
			<!-- Task table -->
			<table class="list table table-striped table-bordered">
				<tr id="tr-one">	
					<th>Task</th>
					<th>Due Date</th>
					<th>Category</th>
					<th>Priority</th>
					<th>Complete</th>

				</tr>
				<!-- Grab list from db -->
				<? while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>  
					<tr>
						<td> <?= $row['task']; ?> </td>
						<td> <?= $row['due_date']; ?> </td>
						<td> <?= $row['category']; ?> </td>
						<td> <?= $row['priority']; ?> </td>
						<td><a id="a-link" href="?remove=<?= $row['id'] ?>"><span id="delete-btn-1">Complete?</span></a></td>
					</tr>
				<? endwhile; ?>
			</table>
		</div>



		<!-- pagination -->
		<div id="button-group" class="container">
			<? if ($page >= 2) : ?>
				<a class="btn btn-primary my-btn bck-btn pull-left" href="?page=<?= --$page; ?>"> &#8592; Previous</a>

				<? ++$page; ?>
			<? endif; ?>

			<? if ($page < $pageCount) : ?>
				<a class="btn btn-primary my-btn pull-right" href="?page=<?= ++$page; ?>">Next &#8594;</a>				  
			<? endif; ?>
		</div>

	</div>


	<!-- Footer -->
	<section id="footer" class="center-text">
		<div class="container">
			<div class="row">
				<h5 class="center-text">Todo List</h5>
				<h6 class="center-text">&copy; Chris Fuhrman</h6>
			</div>
		</div>
	</section>

</body>
</html>