<?php

namespace Tests\Feature\Projet;

use App\Http\Controllers\Projet\AdminIndex;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

#[CoversClass(AdminIndex::class)]
class AdminIndexControllerTest extends TestCase
{
    #[Test]
    public function it_aborts_for_non_admin(): void
    {
        $controller = new AdminIndex();
        $request = Request::create('/projet/admin', 'GET');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(403);

        $controller($request);
    }

    #[Test]
    public function it_renders_for_admin_user(): void
    {
        $controller = new AdminIndex();
        $request = Request::create('/projet/admin', 'GET');
        $request->setUserResolver(function () {
            $user = new class {
                public bool $admin = true;
            };

            return $user;
        });

        $response = $controller($request);

        $this->assertSame('projet.admin.index', $response->getName());
        $this->assertSame('project', $response->getData()['modulepart']);
    }
}
