<?php

namespace App\Repositories\Contracts;

interface CartRepositoryContract
{
	// Discounts
	public function getDiscounts();
	public function addDiscount($couponId);
	public function discountCouponExists($couponId);
	public function removeDiscount($couponId);

	// Products
	public function getProducts();
	public function addProduct($productId, $quantity);
	public function removeProduct($productId);
	public function updateProduct($productId, $quantity);
	public function productExists($productId);
	public function getProductQuantity($productId);
}