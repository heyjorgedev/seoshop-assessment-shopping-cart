<?php

namespace App\Models\Cart;

class CartDiscount
{
	public $couponId;
	public $couponCode;
	public $value;
	public $isPercentage;

	public function __construct()
	{

	}

	public static function create($couponId, $couponCode, $value, $isPercentage)
	{
		$instance = new self();

		$instance->couponId = $couponId;
		$instance->couponCode = $couponCode;
		$instance->value = $value;
		$instance->isPercentage = $isPercentage;

		return $instance;
	}
}