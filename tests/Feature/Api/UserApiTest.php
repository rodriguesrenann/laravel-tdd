<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserApiTest extends TestCase
{
    protected $endpoint = '/api/users';

    /**
     * @dataProvider paginationDataProvider
     */
    public function test_paginate(
        int $total,
        int $page = 1
    ) {
        User::factory()->count($total)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ])
            ->assertJsonFragment(['total' => $total]);
    }

    /**
     * @dataProvider createUserDataProvider
     */
    public function test_create(
        array $payload,
        int $statusCode,
        array $jsonStructure
    ) {
        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus($statusCode);
        $response->assertJsonStructure($jsonStructure);
    }

    public function test_find()
    {
        $user = User::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]);
    }

    public function test_not_found()
    {
        $response = $this->getJson("{$this->endpoint}/984");
        $response->assertStatus(404);
    }

    /**
     * @dataProvider updateUserDataProvider
     */
    public function test_update(
        array $payload,
        int $statusCode,
        array $jsonStructure
    ) {
        $user = User::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$user->id}", $payload);

        $response->assertStatus($statusCode);
        $response->assertJsonStructure($jsonStructure);
    }

    public function test_update_not_found()
    {
        $response = $this->putJson("{$this->endpoint}/{999}");

        $response->assertStatus(404);
    }

    public function paginationDataProvider(): array
    {
        return [
            ['total' => 40]
        ];
    }

    public function updateUserDataProvider(): array
    {
        return [
            'test_update_with_success' => [
                'payload' => [
                    'name' => 'Nome updated',
                    'email' => 'email@email.com',
                    'password' => 'testecom'
                ],
                'statusCode' => 200,
                'jsonStructure' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'test_update_without_password_with_success' => [
                'payload' => [
                    'name' => 'Nome updated',
                    'email' => 'email@email.com',
                ],
                'statusCode' => 200,
                'jsonStructure' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'test_update_with_invalid_payload' => [
                'payload' => [],
                'statusCode' => 422,
                'jsonStructure' => [
                    'errors' => [
                        'email',
                        'name',
                    ]
                ]
            ]
        ];
    }

    public function createUserDataProvider(): array
    {
        return [
            'test_create_with_success' => [
                'payload' => [
                    'name' => 'Renan',
                    'email' => 'renan@teste.com',
                    'password' => 'teste123'
                ],
                'statusCode' => 201,
                'jsonStructure' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'test_invalid_payload' => [
                'payload' => [],
                'statusCode' => 422,
                'jsonStructure' => [
                    'errors' => [
                        'name',
                        'email',
                        'password'
                    ]
                ]
            ]
        ];
    }
}
