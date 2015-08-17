<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SessionCartRepositoryTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGet()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('get')->with("cart.products", [])->andReturn([
        	1 => 1,
        	2 => 2
        ]);
 
        $mockSession->shouldReceive('get')->once()->with('cart.discounts', [])->andReturn([]);

        $mockProductsRepository->shouldReceive('getIn')->once()->with([1, 2])->andReturn([]);

        // Act
        $cart = $sessionCartRepository->get();
        $productCount = $sessionCartRepository->getProductCount();

        // Assert
        $this->assertTrue(array_key_exists('products', $cart));
        $this->assertTrue(array_key_exists('discounts', $cart));
        $this->assertTrue(array_key_exists('total', $cart));
        $this->assertTrue($productCount == 3);
    }

    public function testGetEmpty()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('get')->with("cart.products", [])->andReturn([]);
        
        $mockSession->shouldReceive('get')->once()->with('cart.discounts', [])->andReturn([]);

        // Act
        $cart = $sessionCartRepository->get();
        $productCount = $sessionCartRepository->getProductCount();

        // Assert
        $this->assertTrue(array_key_exists('products', $cart));
        $this->assertTrue(array_key_exists('discounts', $cart));
        $this->assertTrue(array_key_exists('total', $cart));
        $this->assertTrue($productCount == 0);
    }

    public function testAdd()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);
        $product = factory(\App\Models\Product::class, 1)->make();
        $product->id = 1;

        $mockProductsRepository->shouldReceive('get')->once()->with(1)->andReturn($product);
        $mockSession->shouldReceive('get')->once()->with('cart.products.1', 0)->andReturn(5);
        
        // The session should receive 10 + 5;
        $mockSession->shouldReceive('put')->once()->with('cart.products.1', 15); 

        // Act
		$result = $sessionCartRepository->add(1, 10);

		// Assert
		$this->assertTrue($result);
    }

    public function testRemoveLessThanZero()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('get')->once()->with('cart.products.1', 0)->andReturn(5);
        $mockSession->shouldReceive('remove')->once()->with('cart.products.1');

        // Act
		$result = $sessionCartRepository->remove(1, 10);

		// Assert
		$this->assertTrue($result);
    }

    public function testRemoveFiveFromTen()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('get')->once()->with('cart.products.1', 0)->andReturn(10);
        $mockSession->shouldReceive('put')->once()->with('cart.products.1', 5);

        // Act
		$result = $sessionCartRepository->remove(1, 5);

		// Assert
		$this->assertTrue($result);
    }

    public function testRemoveAll()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('remove')->once()->with('cart.products.1');

        // Act
		$result = $sessionCartRepository->remove(1);

		// Assert
		$this->assertTrue($result);
    }

    public function testAddDiscount()
    {
    	// Arrange
        $mockProductsRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockCouponRepository = Mockery::mock('App\Repositories\Contracts\CouponRepositoryContract');
        $mockSession = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $sessionCartRepository = new \App\Repositories\Session\SessionCartRepository($mockSession, $mockProductsRepository, $mockCouponRepository);

        $mockSession->shouldReceive('get')->once()->with('cart.discounts', [])->andReturn([]);

        // Act
        $result = $sessionCartRepository->addDiscount(1);

        // Assert
        $this->assertTrue($result);

    }


}
