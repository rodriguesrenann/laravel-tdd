<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTest extends TestCase
{
    protected function model(): Model
    {
        return new User();
    }

    /**
     * Testa se o model tem as devidas traits
     *
     * @return void
     */
    public function test_model_has_traits()
    {
        $traits = array_keys(class_uses($this->model()));

        $expectedTraits = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];

        $this->assertEquals($expectedTraits, $traits);
    }

    /**
     * Testa se o model tem os devidos fillables
     *
     * @return void
     */
    public function test_model_has_fillables()
    {
        $fillables = $this->model()->getFillable();

        $expectedFillables = [
            'name',
            'email',
            'password'
        ];

        $this->assertEquals($expectedFillables, $fillables);
    }

    /**
     * Testa se o incrementing esta falso
     *
     * @return void
     */
    public function test_incrementing_is_false()
    {
        $this->assertFalse($this->model()->incrementing);
    }

    /**
     * Testa se o model tem os devidos casts
     *
     * @return void
     */
    public function test_model_has_casts()
    {
        $expectedCasts = [
            'email_verified_at' => 'datetime',
        ];

        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedCasts, $casts);
    }
}
