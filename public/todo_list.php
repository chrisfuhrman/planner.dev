<?php

require_once '../inc/filestore.php';

class ToDoListStore extends Filestore

{

}

// initialize class
$todo_list_obj = new ToDoListStore('list.txt');

//calling function to open file, read
$todo_list_obj->contents = $todo_list_obj->read();

// add item to array
if (isset($_POST['item'])) {
	$todo_list_obj->contents[] = htmlspecialchars(strip_tags($_POST['item']));
}

// Call function to save file
$todo_list_obj->write($todo_list_obj->contents);

if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($todo_list_obj->contents[$id]);
}

// Call function to save file
$todo_list_obj->write($todo_list_obj->contents);


// Verify there were uploaded files and no errors
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
	// Set the destination directory for uploads
	$uploadDir = '/vagrant/sites/planner.dev/public/uploads/';

	// Grab the filename from the uploaded file by using basename
	$filename = basename($_FILES['file1']['name']);

	// Create the saved filename using the file's original name and our upload directory
	$savedFilename = $uploadDir . $filename;

	// Move the file from the temp location to our uploads directory
	move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);

	$uploadedArray = read($savedFilename);
	$todo_list_obj->contents = array_merge($uploadedArray, $todo_list_obj->contents);
	$todo_list_obj->write($contents);
}


?>


<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

	<h1 id="title">ToDo List Application</h2>


	<h2 class="form_title">Add an item to your list</h2>

<!-- form to enter a new todo item -->
	<form method="POST" action="/todo_list.php">
		<label for="item"></label>
		<input class="input" type="item" id="item" name="item">
		<button type="submit">Add</button>
	</form>


<!-- File upload code -->
	<form method="POST" enctype="multipart/form-data" action="/todo_list.php">
		<p>
			<label for="file1">File to upload: </label>
			<input type="file" id="file1" name="file1">
		</p>
		<p>
			<input type="submit" value="Upload">
		</p>
	</form>

<!-- List -->
	<div class="list">
		<h2 id="list_title">Your List</h2>
		<ul>
		<? foreach ($todo_list_obj->contents as $key => $item) : ?>
		<div class="li-list">
		<li><?= $item; ?>	
		<a href="?remove=<?= $key; ?>">X</a></div> 
	<? endforeach; ?></li></ul></div>
	

</body>
</html>