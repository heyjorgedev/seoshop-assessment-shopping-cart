<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryContract as ProductRepository;

class ProductsController extends Controller
{
	protected $productRepo;

	public function __construct(ProductRepository $productRepo)
	{
		$this->productRepo = $productRepo;
	}

    public function getIndex()
    {
        $products = $this->productRepo->getAll();
    	return view('products.index', compact('products'));
    }

    public function getDetails($id)
    {
    	$product = $this->productRepo->get($id);
    	return view('products.details', compact('product'));
    }
}
