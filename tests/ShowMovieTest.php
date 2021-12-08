<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ShowMovieTest extends WebTestCase
{
    public function testIfShowingMoviesWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films/titre-1');

        $this->assertResponseStatusCodeSame(200);
    }
}
