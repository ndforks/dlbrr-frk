<?php

namespace Tests\Unit;

use App\Models\Projet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Projet::class)]
class ProjetModelTest extends TestCase
{
    #[Test]
    public function it_has_expected_table_and_key(): void
    {
        $model = new Projet();

        $this->assertSame('llx_projet', $model->getTable());
        $this->assertSame('rowid', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame([], $model->getGuarded());
    }
}
