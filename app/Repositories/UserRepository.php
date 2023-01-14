<?php

namespace App\Repositories;

use App\Models\User;
use App\Presenters\PaginationInterface;
use App\Presenters\PaginationPresenter;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->get()->toArray();
    }

    public function paginate(): PaginationInterface
    {
        $users = $this->model->paginate();

        return new PaginationPresenter($users);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $user = $this->model->find($id);

        $user->update($data);

        return $user;
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }
}
