<?php

namespace App\Repositories\Session;

use App\Repositories\Contracts\CartRepositoryContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SessionContract;
use App\Models\CartItem;

class SessionCartRepository implements CartRepositoryContract
{
	protected $session;

	public function __construct(SessionContract $session)
	{
		$this->session = $session;
	}

	public function productExists($productId)
	{
		return $this->session->has($this->getProductSessionKey($productId));
	}

	public function getProductQuantity($productId)
	{
		return $this->session->get($this->getProductSessionKey($productId), 0);
	}

	public function updateProduct($productId, $quantity)
	{
		$this->session->put($this->getProductSessionKey($productId), $quantity);
	}

	public function addProduct($productId, $quantity)
	{
		$this->session->put($this->getProductSessionKey($productId), $quantity);
	}

	public function removeProduct($productId)
	{
		$this->session->forget($this->getProductSessionKey($productId));
	}


	private function getProductSessionKey($productId)
	{
		return 'cart.products.'.$productId;
	}


	public function getProductsCount()
	{
		return array_sum($this->session->get('cart.products', []));
	}

	public function getProducts()
	{
		return $this->session->get('cart.products', []);
	}

	public function getDiscounts()
	{
		// Get Discounts from the Database
		return $this->session->get('cart.discounts', []); // Default value is an empty array
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