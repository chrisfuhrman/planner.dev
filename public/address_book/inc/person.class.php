<?php

// include model.class file
require_once 'inc/model.class.php';
	
Class Person extends Model
{
	public $attributes = [];

	public function __construct($dbc)
	{
		parent::__construct($dbc);

	}


	protected function insert() {

		$insertData = $this->dbc->prepare('INSERT INTO people (first_name, last_name) 
			VALUES (:first_name, :last_name)
			');

	    $insertData->bindValue(':first_name', $this->attributes['first_name'], PDO::PARAM_STR);
	    $insertData->bindValue(':last_name', $this->attributes['last_name'], PDO::PARAM_STR);

	    $insertData->execute();
	}


	protected function load($id)
	{
		// takes in id and loads data

		$query = $this->dbc->prepare("SELECT * FROM people WHERE id = :id");

		$query->bindValue(':id', $id, PDO::PARAM_INT);

		$query->execute();

		$this->attributes = $query->fetch(PDO::FETCH_ASSOC);

	}

	protected function delete() {

	}
}
