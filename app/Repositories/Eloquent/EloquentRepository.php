<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryContract;

class EloquentRepository implements RepositoryContract
{
	protected $model;

	public function getAll()
	{
		return $this->model->all();
	}

	public function get($id)
	{
		return $this->model->findOrFail($id);
	}

	public function getIn(array $array)
	{
		return $this->model->whereIn('id', $array)->get();
	}

	public function createOrUpdate($data, $id = null)
	{

	}

	public function remove($id)
	{

	}
}