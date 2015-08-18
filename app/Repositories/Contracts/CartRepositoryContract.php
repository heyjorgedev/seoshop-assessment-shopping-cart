<?php

namespace App\Repositories\Contracts;

interface CartRepositoryContract
{
	// Discounts
	public function getDiscounts();
	public function addDiscountCoupon($couponId);
	public function hasDiscountCoupon($couponId);
	public function removeDiscountCoupon($couponId);

	// Products
	public function getProducts();
	public function addProduct($productId, $quantity);
	public function removeProduct($productId);
	public function updateProduct($productId, $quantity);
	public function hasProduct($productId);
	public function getProductQuantity($productId);
}