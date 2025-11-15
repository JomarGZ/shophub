<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

abstract class Repository
{
    public function __construct(protected Model $model) {}

    public function model(): Model
    {
        return $this->model;
    }

    public function query(): Builder
    {
        return $this->model->query();
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->query()->with($relations)->paginate($perPage, $columns);
    }

    public function simplePaginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): Paginator
    {
        return $this->query()->with($relations)->simplePaginate($perPage, $columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function transaction(\Closure $callback)
    {
        return DB::transaction($callback);
    }

    public function createWithRelation(array $data, array $relations = [])
    {
        return $this->transaction(function () use ($data, $relations) {
            $model = $this->create($data);
            foreach ($relations as $relation => $relationData) {
                if (! method_exists($model, $relation)) {
                    continue;
                }
                $relationInstance = $model->relation();

                if (! $relationInstance instanceof Relation) {
                    continue;
                }
                if ($relationInstance instanceof BelongsToMany) {
                    $relationInstance->sync($relationData);
                } elseif (is_array($relationData) && isset($relationData[0])) {
                    $relationInstance->createMany($relationData);
                } else {
                    $relationInstance->create($relationData);
                }
            }
        });
    }
}
