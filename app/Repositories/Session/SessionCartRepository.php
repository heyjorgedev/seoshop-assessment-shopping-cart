<?php

namespace App\Repositories\Session;

use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Repositories\Contracts\CouponRepositoryContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SessionContract;
use App\Models\CartItem;

class SessionCartRepository implements CartRepositoryContract
{
	protected $session;
	protected $productRepo;
	protected $couponRepo;
	private $products;

	public function __construct(SessionContract $session, ProductRepositoryContract $productRepo, CouponRepositoryContract $couponRepo)
	{
		$this->session = $session;
		$this->productRepo = $productRepo;
		$this->couponRepo = $couponRepo;
	}

	public function get()
	{
		// This is a contract
		// Get Should always return this array
		// It is on the tests
		return [
			'products' 	=> $this->getProducts(),
			'discounts'	=> $this->getDiscounts(),
			'total'		=> $this->getTotal()
		];
	}

	public function getQuantity($id)
	{
		return $this->session->get('cart.products.'.$id, 0); // Default value is 0
	}

	public function getProductCount()
	{
		return array_sum($this->session->get('cart.products', []));
	}

	public function getTotal()
	{
		// Start with the Total of 0
		$total = 0;

		// Go to each product and sum the value
		foreach($this->getProducts() as $product)
		{
			$total = $total + $product->getSubTotal();
		}

		// I could use a ternary operator here like the one commented below
		// but its harder to read than a simple IF Statement
		// $total = $total > 0 ? $total : 0;
		if($total <= 0)
		{
			$total = 0;
		}

		// Return the Formated Number
		return number_format($total, 2, '.', '');
	}

	public function getProducts()
	{
		// Built some sort of a cache in memory to reduce the Database Calls to 1 per request
		if(isset($this->products)) return $this->products;
		else $this->products = [];

		// Get the Session Products
		$cartItems = $this->session->get('cart.products', []);
		if(empty($cartItems)) return $cartItems;
		
		// Get the Products from the Database by range of IDs
		$products = $this->productRepo->getIn(array_keys($cartItems));
		if(empty($products)) return $products;

		// Loop Products and Map to the CartItem Object
		foreach($products as $product)
		{
			$cartItem = CartItem::create($product->id, $product->title, $cartItems[$product->id], $product->price);
			array_push($this->products, $cartItem);
		}

		// Return the Database Products
		return $this->products;
	}

	public function getDiscounts()
	{
		// Get Discounts from the Database
		return $this->session->get('cart.discounts', []); // Default value is an empty array
	}

	public function add($id, $quantity = 1)
	{
		try
		{
			// Verify if the Product really exists
			// it will throw an exception if the product doesnt exist.
			$product = $this->productRepo->get($id);

			// we need to get the previous quantity
			$newQuantity = $this->getQuantity($product->id) + $quantity;

			// we need to update the cart with the new quantity
			$this->session->put('cart.products.'.$product->id, $newQuantity);

			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}

	public function remove($id, $quantity = null)
	{
		try
		{
			// If the $quantity parameter is null
			// we want to remove this product no matter the quantity
			if(is_null($quantity))
			{
				$this->session->remove('cart.products.'.$id);
				return true;
			}

			// if it is not null we want to update the quantity
			$newQuantity = $this->getQuantity($id) - $quantity;

			// but if the quantity is equal or less than 0
			// we want to remove it from the cart
			if($newQuantity <= 0)
			{
				$this->session->remove('cart.products.'.$id);
				return true;
			}

			// Otherwise we want to update the cart quantity of this item
			$this->session->put('cart.products.'.$id, $newQuantity);

			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
		
	}

	public function addDiscount($couponId)
	{
		// Get the Discounts
		$discounts = $this->getDiscounts();

		// Verify if the Discount Already Exists
		if(array_key_exists($couponId, $discounts))
			return false;

		return true;
	}
}