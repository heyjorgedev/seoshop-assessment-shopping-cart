<?php

namespace App\Models\Cart;

class CartItem
{
	public $productId;
	public $productName;
	public $quantity;
	public $unitPrice;

	public function __construct()
	{

	}

	public static function create($productId, $productName, $quantity, $unitPrice)
	{
		$instance = new self();

		$instance->productId = $productId;
		$instance->productName = $productName;
		$instance->quantity = $quantity;
		$instance->unitPrice = number_format($unitPrice, 2, '.', '');

		return $instance;
	}

	public function getSubTotal()
	{
		return number_format($this->unitPrice * $this->quantity, 2, '.', '');
	}
}