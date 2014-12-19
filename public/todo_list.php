<?php

//function to open a file, read it, and turn contents into an array
function readList($filename) {

    $handle = fopen($filename, 'r');

    if (filesize($filename) > 0) {
        $contents = fread($handle, filesize($filename));
        $listArray = explode(PHP_EOL, trim($contents));
    } else {
        $listArray = [];
    }
    
    fclose($handle); 

    return $listArray;
}




// Function to save todo list to a file
function saveFile($filename, $items) {

    $handle = fopen($filename, 'w');

    foreach ($items as $task) {
        fwrite($handle, $task . PHP_EOL);
    }
    
    fclose($handle);
}

//calling function to open file, read, and turn list into array
$filename = 'list.txt';
$listArray  = readList($filename);

// add item to array
if (isset($_POST['item'])) {
	$listArray[] = $_POST['item'];
}

// Call function to save file
saveFile($filename, $listArray);

if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($listArray[$id]);
}

// Call function to save file
saveFile($filename, $listArray);

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

<!-- List -->
	<div class="list">
		<h2 id="list_title">Your List</h2>
		<ul>
<?php

	foreach ($listArray as $key => $item) {
		echo '<div class="li-list">' . "<li>{$item} | <a href=\"?remove={$key}\">X</a></div>";
	}	echo ' </li>';

?>
		</ul>
	</div>








</body>
</html>