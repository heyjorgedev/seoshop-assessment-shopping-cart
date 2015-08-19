<?php

namespace App\Services\Exceptions;

class ProductNotFoundException extends ValidationException
{
	public function __construct()
	{
		parent::__construct('The product was not found', 0, null);
	}

	public function getErrors()
	{
		return array();
	}
}