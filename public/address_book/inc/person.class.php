<?php

require_once 'model.class.php';

Class Person extends Model
{

	public function insert()
	{
		$insertData = $this->dbc->prepare('INSERT INTO people (first_name, last_name) 
										   VALUES (:first_name, :last_name)');

	    $insertData->bindValue(':first_name', $this->attributes['first_name'], PDO::PARAM_STR);
	    $insertData->bindValue(':last_name',  $this->attributes['last_name'],  PDO::PARAM_STR);

	    $insertData->execute();
	}

	public function update()
	{
		$updateStmt = $this->dbc->prepare('UPDATE people 
									       SET first_name = :first_name, last_name = :last_name
									       WHERE id = :id');

		$updateStmt->bindValue(':id',         $this->attributes['id'],         PDO::PARAM_INT);
		$updateStmt->bindValue(':first_name', $this->attributes['first_name'], PDO::PARAM_STR);
		$updateStmt->bindValue(':last_name',  $this->attributes['last_name'],  PDO::PARAM_STR);

		$updateStmt->execute();

	}

	public function delete()
	{

		$query = $this->dbc->prepare("DELETE FROM people 
			                          WHERE id = :id");

		$query->bindValue(':id', $this->attributes['id'], PDO::PARAM_INT);

		$query->execute();

	}

	public function load($id)
	{
		// takes in id and loads data

		$query = $this->dbc->prepare("SELECT * FROM people 
			                          WHERE id = :id");

		$query->bindValue(':id', $id, PDO::PARAM_INT);

		$query->execute();

		$this->attributes = $query->fetch(PDO::FETCH_ASSOC);

	}

}

