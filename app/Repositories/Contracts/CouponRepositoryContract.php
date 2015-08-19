<?php

namespace App\Repositories\Contracts;

interface CouponRepositoryContract extends RepositoryContract
{
	public function getByCode($couponCode);
}