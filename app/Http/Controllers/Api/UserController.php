<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $response = $this->repository->paginate();

        return UserResource::collection(collect($response->items()))
            ->additional([
                'meta' => [
                    'total' => $response->total(),
                    'current_page' => $response->currentPage(),
                    'last_page' => $response->lastPage(),
                    'first_page' => $response->firstPage(),
                    'per_page' => $response->perPage(),
                ]
            ]);
    }

    public function store(UserRequest $request)
    {
        $resource = $this->repository->create($request->validated());

        return new UserResource($resource);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $request->validated();
        $resource = $this->repository->update($user->id, $data);

        return new UserResource($resource);
    }
}
