<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    public function __construct(protected Model $model) {}

    public function model()
    {
        return $this->model;
    }

    public function query()
    {
        return $this->model->query();
    }
}
