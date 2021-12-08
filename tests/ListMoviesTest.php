<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ListMoviesTest extends WebTestCase
{
    public function testIfListingMoviesWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testIfPaginationWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films?page=2');

        $this->assertResponseStatusCodeSame(200);
    }
}
