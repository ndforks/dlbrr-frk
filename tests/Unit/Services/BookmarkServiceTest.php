<?php

namespace Tests\Unit\Services;

use App\Models\Bookmark;
use App\Models\User;
use App\Services\BookmarkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookmarkServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookmarkService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookmarkService();
    }

    #[Test]
    public function it_retrieves_paginated_bookmarks(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'firstname' => 'User',
            'entity' => 1,
        ]);

        Bookmark::create([
            'title' => 'First Bookmark',
            'url' => 'https://example.com/1',
            'fk_user' => $user->rowid,
            'position' => 1,
        ]);

        Bookmark::create([
            'title' => 'Second Bookmark',
            'url' => 'https://example.com/2',
            'fk_user' => $user->rowid,
            'position' => 2,
        ]);

        // Act
        $result = $this->service->getList([], 'position', 'ASC', 10, 0);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('bookmarks', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals(2, $result['total']);
    }

    #[Test]
    public function it_filters_bookmarks_by_title(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'entity' => 1,
        ]);

        Bookmark::create([
            'title' => 'Laravel Documentation',
            'url' => 'https://laravel.com',
            'fk_user' => $user->rowid,
            'position' => 1,
        ]);

        Bookmark::create([
            'title' => 'PHP Documentation',
            'url' => 'https://php.net',
            'fk_user' => $user->rowid,
            'position' => 2,
        ]);

        // Act
        $result = $this->service->getList(
            ['title' => 'Laravel'],
            'position',
            'ASC',
            10,
            0
        );

        // Assert
        $this->assertEquals(1, $result['total']);
        $this->assertStringContainsString('Laravel', $result['bookmarks'][0]->title);
    }

    #[Test]
    public function it_retrieves_bookmark_by_id(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'entity' => 1,
        ]);

        $bookmark = Bookmark::create([
            'title' => 'Test Bookmark',
            'url' => 'https://example.com',
            'fk_user' => $user->rowid,
            'position' => 1,
        ]);

        // Act
        $retrieved = $this->service->getById($bookmark->rowid);

        // Assert
        $this->assertNotNull($retrieved);
        $this->assertEquals('Test Bookmark', $retrieved->title);
    }

    #[Test]
    public function it_creates_new_bookmark(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'entity' => 1,
        ]);

        $data = [
            'title' => 'New Bookmark',
            'url' => 'https://example.com',
            'fk_user' => $user->rowid,
        ];

        // Act
        $bookmark = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals('New Bookmark', $bookmark->title);
        $this->assertNotNull($bookmark->position);
    }

    #[Test]
    public function it_updates_existing_bookmark(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'entity' => 1,
        ]);

        $bookmark = Bookmark::create([
            'title' => 'Original Title',
            'url' => 'https://example.com',
            'fk_user' => $user->rowid,
            'position' => 1,
        ]);

        // Act
        $result = $this->service->update($bookmark->rowid, [
            'title' => 'Updated Title',
        ]);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('llx_bookmark', [
            'rowid' => $bookmark->rowid,
            'title' => 'Updated Title',
        ]);
    }

    #[Test]
    public function it_deletes_bookmark(): void
    {
        // Arrange
        $user = User::create([
            'login' => 'testuser',
            'lastname' => 'Test',
            'entity' => 1,
        ]);

        $bookmark = Bookmark::create([
            'title' => 'To Delete',
            'url' => 'https://example.com',
            'fk_user' => $user->rowid,
            'position' => 1,
        ]);

        $bookmarkId = $bookmark->rowid;

        // Act
        $result = $this->service->delete($bookmarkId);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('llx_bookmark', ['rowid' => $bookmarkId]);
    }

    #[Test]
    public function it_retrieves_bookmarks_by_user(): void
    {
        // Arrange
        $user1 = User::create([
            'login' => 'user1',
            'lastname' => 'User',
            'firstname' => 'One',
            'entity' => 1,
        ]);

        $user2 = User::create([
            'login' => 'user2',
            'lastname' => 'User',
            'firstname' => 'Two',
            'entity' => 1,
        ]);

        Bookmark::create([
            'title' => 'User 1 Bookmark',
            'url' => 'https://example.com/1',
            'fk_user' => $user1->rowid,
            'position' => 1,
        ]);

        Bookmark::create([
            'title' => 'User 2 Bookmark',
            'url' => 'https://example.com/2',
            'fk_user' => $user2->rowid,
            'position' => 1,
        ]);

        // Act
        $bookmarks = $this->service->getByUser($user1->rowid);

        // Assert
        $this->assertCount(1, $bookmarks);
        $this->assertEquals('User 1 Bookmark', $bookmarks[0]->title);
    }
}
