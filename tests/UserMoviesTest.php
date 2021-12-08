<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserMoviesTest extends WebTestCase
{
    public function testIfUserListingMoviesWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/utilisateurs/user+1');

        $this->assertResponseStatusCodeSame(200);
    }
}
