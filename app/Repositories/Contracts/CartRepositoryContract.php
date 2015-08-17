<?php

namespace App\Repositories\Contracts;

interface CartRepositoryContract
{
	public function get();
	public function getQuantity($id);
	public function add($id, $quantity = 1);
	public function remove($id, $quantity = null);
	public function getProducts();
	public function getProductCount();
	public function getDiscounts();
	public function getTotal();
	public function addDiscount($couponId);
}