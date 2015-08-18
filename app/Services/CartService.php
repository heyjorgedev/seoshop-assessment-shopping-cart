<?php

namespace App\Services;

use App\Services\Contracts\CartServiceContract;
use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Models\CartItem;

class CartService implements CartServiceContract
{
	protected $cartRepository;
	protected $productRepository;

	private $products;
	private $discounts;
	private $total;

	public function __construct(CartRepositoryContract $cartRepository, ProductRepositoryContract $productRepository)
	{
		$this->cartRepository = $cartRepository;
		$this->productRepository = $productRepository;
	}

	public function get()
	{
		return (object)[
			'products'	=> $this->getProducts(),
			'discounts'	=> $this->getDiscounts(),
			'total'		=> $this->getTotal()
		];
	}

	public function getProducts()
	{
		if(isset($this->products)) return $this->products;
		else $this->products = [];

		// Get the Session Products
		$cartProducts = $this->cartRepository->getProducts();
		if(empty($cartProducts)) return $cartProducts;
		
		// Get the Products from the Database by range of IDs
		$products = $this->productRepository->getIn(array_keys($cartProducts));
		if(empty($products)) return $products;

		// Loop Products and Map to the CartItem Object
		foreach($products as $product)
		{
			$cartItem = CartItem::create($product->id, $product->title, $cartProducts[$product->id], $product->price);
			array_push($this->products, $cartItem);
		}

		// Return the Database Products
		return $this->products;
	}

	public function getProductsCount()
	{
		$count = 0;

		foreach($this->getProducts() as $product)
		{
			$count += intval($product->quantity);
		}

		return $count;
	}

	public function getDiscounts()
	{
		if(isset($this->discounts)) return $this->discounts;
		else $this->discounts = [];
	}

	public function getTotal()
	{
		if(isset($this->total)) return $this->total;
		else $this->total = 0;

		// Go to each product and sum the value
		foreach($this->getProducts() as $product)
		{
			$this->total += $product->getSubTotal();
		}

		// I could use a ternary operator here like the one commented below
		// but its harder to read than a simple IF Statement
		// $this->total = $this->total > 0 ? $this->total : 0;
		if($this->total <= 0)
		{
			$this->total = 0;
		}

		$this->total = number_format($this->total, 2, '.', '');

		// Return the Formated Number
		return $this->total;
	}

	public function addProduct($productId, $quantity)
	{
		if($this->cartRepository->productExists($productId))
		{
			$newQuantity = $this->cartRepository->getProductQuantity($productId) + $quantity;
			$this->cartRepository->updateProduct($productId, $newQuantity);

			return true;
		}
		
		$this->cartRepository->addProduct($productId, $quantity);
		return true;
	}

	public function removeProduct($productId, $quantity)
	{
		if( ! $this->cartRepository->productExists($productId)) return false;

		$newQuantity = $this->cartRepository->getProductQuantity($productId) - $quantity;

		if($newQuantity <= 0)
		{
			$this->cartRepository->removeProduct($productId);
			return true;
		}

		$this->cartRepository->updateProduct($productId, $newQuantity);
		return true;
	}

	public function addCoupon($couponId)
	{

	}

	public function removeCoupon($couponId)
	{

	}
}