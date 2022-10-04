<?php

namespace Tests\Feature\App\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\QueryException;

class UserRepositoryTest extends TestCase
{
    protected $repository;

    public function setUp(): void
    {
        $this->repository = new UserRepository(new User());
        parent::setUp();
    }

    /**
     * Testa se implementa a interface
     *
     * @return void
     */
    public function test_implements_interface()
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }

    /**
     * Testa o método get all da repository
     *
     * @return void
     */
    public function test_get_all_empty()
    {
        $response = $this->repository->getAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    /**
     * Testa o método get all com dados
     *
     * @return void
     */
    public function test_get_all()
    {
        User::factory()->count(10)->create();

        $response = $this->repository->getAll();

        $this->assertIsArray($response);
        $this->assertCount(10, $response);
    }

    /**
     * Testa o método create
     *
     * @return void
     */
    public function test_create()
    {
        $data = [
            'name' => 'Renan Rodrigues',
            'email' => 'renan@teste.com',
            'password' => bcrypt('12345678')
        ];

        $response = $this->repository->create($data);

        $this->assertIsObject($response);
        $this->assertNotNull($response);
        $this->assertDatabaseHas(
            'users',
            [
                'email' => 'renan@teste.com'
            ]
        );
    }

    /**
     * Teste exception no banco
     *
     * @return void
     */
    public function test_exception()
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'Renan Rodrigues',
            'password' => bcrypt('12345678')
        ];

        $this->repository->create($data);
    }

    /**
     * Testa o update
     *
     * @return void
     */
    public function test_update()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'new name'
        ];

        $response = $this->repository->update($user->id, $data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'new name'
        ]);
    }

    /**
     * Testa o método find
     *
     * @return void
     */
    public function test_find()
    {
        $user = User::factory()->create();

        $response = $this->repository->find($user->id);

        $this->assertIsObject($response);
    }

    /**
     * Testa o método find porem quando o resultado é nulo
     *
     * @return void
     */
    public function test_find_when_null()
    {
        $response = $this->repository->find(2);

        $this->assertNull($response);
    }
}
