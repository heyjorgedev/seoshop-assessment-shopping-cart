<?php

namespace App\Services\Exceptions;

class CouponNotFoundException extends ValidationException
{
	public function __construct()
	{
		parent::__construct('The coupon was not found', 0, null);
	}

	public function getErrors()
	{
		return array();
	}
}