<?php

namespace Tests\Unit;

use App\Models\Facture;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Facture::class)]
class FactureModelTest extends TestCase
{
    #[Test]
    public function it_has_expected_table_and_key(): void
    {
        $model = new Facture();

        $this->assertSame('llx_facture', $model->getTable());
        $this->assertSame('rowid', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame([], $model->getGuarded());
    }
}
