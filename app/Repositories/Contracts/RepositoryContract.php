<?php

namespace App\Repositories\Contracts;

interface RepositoryContract
{
	public function getAll();
	public function get($id);
	public function has($id);
	public function getIn(array $array);
	public function createOrUpdate($data, $id = null);
	public function remove($id);
}