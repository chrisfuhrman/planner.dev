<?php

Class Model 

{
	
	public $dbc;
	public $attributes = [];
	

	public function __construct($dbc) 
	{
		$this->dbc = $dbc;
	}

	public function save()
	{
		if (isset($this->attributes['id'])) {
			return $this->update();
		} else {
			return $this->insert();
		}
	}

	public function __set($name, $value) {
		$this->attributes[$name] = trim($value);
	}

	public function __get($name) {
		return strip_tags($this->attributes[$name]);
	}

	// abstract protected function insert();
	// abstract protected function update();

}