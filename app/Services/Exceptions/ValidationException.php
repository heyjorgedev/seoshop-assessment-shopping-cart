<?php

namespace App\Services\Exceptions;

class ValidationException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function getErrors()
	{
		return array();
	}
}