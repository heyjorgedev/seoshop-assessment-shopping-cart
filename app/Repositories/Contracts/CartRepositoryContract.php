<?php

namespace App\Repositories\Contracts;

interface CartRepositoryContract
{
	public function getProducts();
	public function getDiscounts();
	public function addDiscount($couponId);

	public function productExists($productId);
	public function getProductQuantity($productId);
	public function updateProduct($productId, $quantity);
	public function addProduct($productId, $quantity);
	public function removeProduct($productId);
}