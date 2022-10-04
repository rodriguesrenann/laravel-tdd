<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getAll(): array;
    public function create (array $data): object;
    public function update(int $id, array $data): object;
    public function find (int $id): ?object;
}
