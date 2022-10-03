<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function expectedTraits(): array;
    abstract protected function expectedCasts(): array;
    abstract protected function expectedFillables(): array;

      /**
     * Testa se o model tem as devidas traits
     *
     * @return void
     */
    public function test_model_has_traits()
    {
        $traits = array_keys(class_uses($this->model()));

        $this->assertEquals($this->expectedTraits(), $traits);
    }

    /**
     * Testa se o model tem os devidos fillables
     *
     * @return void
     */
    public function test_model_has_fillables()
    {
        $fillables = $this->model()->getFillable();

        $this->assertEquals($this->expectedFillables(), $fillables);
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
        $casts = $this->model()->getCasts();

        $this->assertEquals($this->expectedCasts(), $casts);
    }
}
