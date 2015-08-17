<?php

namespace App\Models;

class CartItem
{
	public $productId;
	public $productName;
	public $quantity;
	public $unit_price;

	public function __construct()
	{

	}

	public static function create($productId, $productName, $quantity, $unit_price)
	{
		$instance = new self();

		$instance->productId = $productId;
		$instance->productName = $productName;
		$instance->quantity = $quantity;
		$instance->unit_price = number_format($unit_price, 2, '.', '');

		return $instance;
	}

	public function getSubTotal()
	{
		return number_format($this->unit_price * $this->quantity, 2, '.', '');
	}
}