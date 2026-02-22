<?php

namespace Tests\Unit;

use App\Models\Contact;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Contact::class)]
class ContactModelTest extends TestCase
{
    #[Test]
    public function it_has_expected_table_and_key(): void
    {
        $model = new Contact();

        $this->assertSame('llx_socpeople', $model->getTable());
        $this->assertSame('rowid', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame([], $model->getGuarded());
    }
}
