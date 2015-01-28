<?php

require_once 'model.class.php';

Class Address extends Model
{

	public function insert()
	{
		$insertData = $this->dbc->prepare('INSERT INTO addresses (address, city, state, zip, person_id) 
			                               VALUES (:address, :city, :state, :zip, :person_id)');

	    $insertData->bindValue(':address',   $this->attributes['address'],   PDO::PARAM_STR);
	    $insertData->bindValue(':city',      $this->attributes['city'],      PDO::PARAM_STR);
	    $insertData->bindValue(':state',     $this->attributes['state'],     PDO::PARAM_STR);
	    $insertData->bindValue(':zip',       $this->attributes['zip'],       PDO::PARAM_STR);
	    $insertData->bindValue(':person_id', $this->attributes['person_id'], PDO::PARAM_STR);

	    $insertData->execute();
		
	}

	public function update()
	{
		$updateStmt = $this->dbc->prepare('UPDATE addresses 
										   SET address = :address, city = :city, state = :state, zip = :zip 
										   WHERE id = :id');

		$updateStmt->bindValue(':id',      $this->attributes['id'],      PDO::PARAM_INT);
		$updateStmt->bindValue(':address', $this->attributes['address'], PDO::PARAM_STR);
		$updateStmt->bindValue(':city',    $this->attributes['city'],    PDO::PARAM_STR);
		$updateStmt->bindValue(':state',   $this->attributes['state'],   PDO::PARAM_STR);
		$updateStmt->bindValue(':zip',     $this->attributes['zip'],     PDO::PARAM_STR);

		$updateStmt->execute();
	}

	public function delete()
	{

		$query = $this->dbc->prepare("DELETE FROM addresses 
									  WHERE id = :id");

		$query->bindValue(':id', $this->attributes['id'], PDO::PARAM_INT);

		$query->execute();

	}

	public function load($id)
	{
		// takes in id and loads data

		$query = $this->dbc->prepare("SELECT * FROM addresses 
			                          WHERE id = :id");

		$query->bindValue(':id', $id, PDO::PARAM_INT);

		$query->execute();

		$this->attributes = $query->fetch(PDO::FETCH_ASSOC);

	}

}
