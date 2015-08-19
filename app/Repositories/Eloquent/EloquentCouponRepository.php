<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CouponRepositoryContract;
use App\Models\Coupon;

class EloquentCouponRepository extends EloquentRepository implements CouponRepositoryContract
{
	public function __construct(Coupon $model)
	{
		$this->model = $model;
	}

	public function getByCode($couponCode)
	{
		return $this->model->where('code', $couponCode)->first();
	}
}