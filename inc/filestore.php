<?php

class Filestore {

	public $filename = '';

	// Allows filename to be set on instantiation
	function __construct($filename) {
		$this->filename = $filename;
	}

	//function to open a file, read it, and turn contents into an array
	function readLines() {

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
	function writeLines($listArray) {

		$handle = fopen($this->filename, 'w');

		foreach ($listArray as $task) {
			fwrite($handle, $task . PHP_EOL);
		}
		
		fclose($handle);
	}

	// function for reading CSV
	function readCSV() {

		$handle = fopen($this->filename, 'r');

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

	// php function to Save to CSV file
	function writeCSV() {
		$handle = fopen($this->filename, 'w');
		foreach ($this->address_book as $row) {
			fputcsv($handle, $row);	
		}

		fclose($handle);
	}







}