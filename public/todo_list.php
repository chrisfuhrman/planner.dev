<?php

class TodoListStore {

	public $filename = '';

	public $listArray = '';

	// Allows filename to be set on instantiation
	function __construct($filename = 'list.txt') {
		$this->filename = $filename;
	}

	//function to open a file, read it, and turn contents into an array
	function readList() {

		$handle = fopen($this->filename, 'r');

		if (filesize($this->filename) > 0) {
			$contents = fread($handle, filesize($this->filename));
			$listArray = explode(PHP_EOL, trim($contents));
		} else {
			$listArray = [];
		}
		
		fclose($handle); 

		return $listArray;
	}

	// Function to save todo list to a file
	function saveFile($listArray) {

		$handle = fopen($this->filename, 'w');

		foreach ($listArray as $task) {
			fwrite($handle, $task . PHP_EOL);
		}
		
		fclose($handle);
	}
}

// initialize class
$todo_list_obj = new TodoListStore;

//calling function to open file, read
$todo_list_obj->listArray = $todo_list_obj->readList();

// add item to array
if (isset($_POST['item'])) {
	$todo_list_obj->listArray[] = htmlspecialchars(strip_tags($_POST['item']));
}

// Call function to save file
$todo_list_obj->saveFile($todo_list_obj->listArray);

if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($todo_list_obj->listArray[$id]);
}

// Call function to save file
$todo_list_obj->saveFile($todo_list_obj->listArray);


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

	$uploadedArray = readList($savedFilename);
	$todo_list_obj->listArray = array_merge($uploadedArray, $todo_list_obj->listArray);
	$todo_list_obj->saveFile($listArray);
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
		<? foreach ($todo_list_obj->listArray as $key => $item) : ?>
		<div class="li-list">
		<li><?= $item; ?>	
		<a href="?remove=<?= $key; ?>">X</a></div> 
	<? endforeach; ?></li></ul></div>
	

</body>
</html>