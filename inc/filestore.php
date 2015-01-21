<?php

class Filestore {

	public $filename, 
	$contents = [];
	protected $isCSV = false;


	// Allows filename to be set on instantiation
	function __construct($filename) {
		
		$this->filename = $filename;
		// checks to see if the file is a CSV
		if (substr($filename, -3) == 'csv') {
			$this->isCSV = true;
		}

	} 

	public function read() {

		if ($this->isCSV) { 
			return $this->readCSV();
		} else {
			return $this->readLines();
		}
	}

	public function write () {
		if ($this->isCSV) {
			return $this->writeCSV();
		} else {
			return $this->writeLines();
		}
	}

	//function to open a file, read it, and turn contents into an array
	protected function readLines() {

		$handle = fopen($this->filename, 'r');

		if (filesize($this->filename) > 0) {
			$content = fread($handle, filesize($this->filename));
			$this->contents = explode(PHP_EOL, trim($content));
		} else {
			$this->contents = [];
		}
		
		fclose($handle); 

		return $this->contents;
	}

	// Function to save todo list to a file
	protected function writeLines() {

		$handle = fopen($this->filename, 'w');

		foreach ($this->contents as $task) {
			fwrite($handle, $task . PHP_EOL);
		}
		
		fclose($handle);
	}

	// function for reading CSV
	protected function readCSV() {

		$handle = fopen($this->filename, 'r');

		$this->contents = [];


		while(!feof($handle)) {
			$row = fgetcsv($handle);

			if (!empty($row)) {
				$this->contents[] = $row;
			}
		}
		fclose($handle);
		return $this->contents;
	}

	// php function to Save to CSV file
	protected function writeCSV() {
		$handle = fopen($this->filename, 'w');
		foreach ($this->contents as $row) {
			fputcsv($handle, $row);	
		}

		fclose($handle);
	}

}