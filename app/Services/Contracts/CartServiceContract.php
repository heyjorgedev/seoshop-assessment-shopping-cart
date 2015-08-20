<?php

namespace App\Services\Contracts;

interface CartServiceContract
{
	public function get();
	public function getProducts();
	public function getProductsCount();
	public function getDiscounts();
	public function getTotal();

	public function addProduct($productId, $quantity);
	public function removeProduct($productId, $quantity);

	public function addCoupon($couponId);
	public function addCouponByCode($couponCode);
	public function removeCoupon($couponId);

	public function clear();
}