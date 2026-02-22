<?php

namespace Tests\Unit\Services;

use App\Models\BankAccount;
use App\Services\BankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BankServiceTest extends TestCase
{
    use RefreshDatabase;

    private BankService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BankService();
    }

    #[Test]
    public function it_retrieves_paginated_bank_accounts(): void
    {
        // Arrange
        BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Main Bank Account',
            'number' => '123456789',
            'entity' => 1,
        ]);

        BankAccount::create([
            'ref' => 'BNK002',
            'label' => 'Secondary Account',
            'number' => '987654321',
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->getList([], 'label', 'ASC', 10, 0, 1);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('accounts', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
        $this->assertCount(2, $result['accounts']);
    }

    #[Test]
    public function it_filters_accounts_by_ref(): void
    {
        // Arrange
        BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Main Bank Account',
            'entity' => 1,
        ]);

        BankAccount::create([
            'ref' => 'SAV001',
            'label' => 'Savings Account',
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->getList(
            ['ref' => 'BNK'],
            'ref',
            'ASC',
            10,
            0,
            1
        );

        // Assert
        $this->assertEquals(1, $result['total']);
        $this->assertEquals('BNK001', $result['accounts'][0]->ref);
    }

    #[Test]
    public function it_retrieves_account_by_id(): void
    {
        // Arrange
        $account = BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Main Bank Account',
            'entity' => 1,
        ]);

        // Act
        $retrieved = $this->service->getById($account->rowid);

        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('BNK001', $retrieved->ref);
    }

    #[Test]
    public function it_creates_new_bank_account(): void
    {
        // Arrange
        $data = [
            'ref' => 'NEW001',
            'label' => 'New Account',
            'number' => '111222333',
            'entity' => 1,
        ];

        // Act
        $account = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(BankAccount::class, $account);
        $this->assertEquals('NEW001', $account->ref);
        $this->assertDatabaseHas('llx_bank_account', ['ref' => 'NEW001']);
    }

    #[Test]
    public function it_updates_existing_account(): void
    {
        // Arrange
        $account = BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Original Label',
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->update($account->rowid, [
            'label' => 'Updated Label',
        ]);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_bank_account', [
            'ref' => 'BNK001',
            'label' => 'Updated Label',
        ]);
    }

    #[Test]
    public function it_deletes_account(): void
    {
        // Arrange
        $account = BankAccount::create([
            'ref' => 'DEL001',
            'label' => 'To Delete',
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->delete($account->rowid);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_bank_account', ['ref' => 'DEL001']);
    }

    #[Test]
    public function it_closes_account(): void
    {
        // Arrange
        $account = BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Active Account',
            'clos' => 0,
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->close($account->rowid);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_bank_account', [
            'ref' => 'BNK001',
            'clos' => 1,
        ]);
    }

    #[Test]
    public function it_reopens_account(): void
    {
        // Arrange
        $account = BankAccount::create([
            'ref' => 'BNK001',
            'label' => 'Closed Account',
            'clos' => 1,
            'entity' => 1,
        ]);

        // Act
        $result = $this->service->reopen($account->rowid);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_bank_account', [
            'ref' => 'BNK001',
            'clos' => 0,
        ]);
    }
}
