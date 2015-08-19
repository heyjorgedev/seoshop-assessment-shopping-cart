<?php

namespace App\Services\Exceptions;

class CouponAlreadyExistsException extends ValidationException
{
	public function __construct()
	{
		parent::__construct('The coupon was already added', 0, null);
	}

	public function getErrors()
	{
		return array();
	}
}