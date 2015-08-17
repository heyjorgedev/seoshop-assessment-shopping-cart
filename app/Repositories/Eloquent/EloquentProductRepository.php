<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ProductRepositoryContract;
use App\Models\Product;

class EloquentProductRepository extends EloquentRepository implements ProductRepositoryContract
{
	public function __construct(Product $model)
	{
		$this->model = $model;
	}
}