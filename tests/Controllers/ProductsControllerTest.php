<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductsControllerTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetIndexAction()
    {
        // Arrange
        $products = factory(App\Models\Product::class, 10)->make();
        $mockRepository = Mockery::mock('App\Repositories\Contracts\ProductRepositoryContract');
        $mockRepository->shouldReceive('getAll')->once()->andReturn($products);
        $this->app->instance('App\Repositories\Contracts\ProductRepositoryContract', $mockRepository);

        // Act
        $this->visit('/products');
        
        // Assert
        $this->assertViewHas('products');
        $this->see($products[0]->name);
        $this->see($products[0]->price);

        $this->see($products[9]->name);
        $this->see($products[9]->price);
    }
}
