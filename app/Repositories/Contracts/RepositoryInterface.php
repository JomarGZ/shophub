<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Model;
    public function findOrFail(int $id): Model;
    public function create(array $data): Model;
    public function update(Model $model, array $data): bool;
    public function delete (Model $model): bool;
    public function paginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function where(string $column, $value): Collection;
}
