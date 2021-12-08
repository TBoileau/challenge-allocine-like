<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DeleteMovieTest extends WebTestCase
{
    public function testIfMovieDeletionOccuredAnForbiddenError(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films/titre-1/supprimer');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testIfMovieDeletionWorks(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-1/supprimer');

        $this->assertResponseStatusCodeSame(302);

        $movieRepository = $client->getContainer()->get(MovieRepository::class);

        $this->assertEquals(49, $movieRepository->count([]));
    }

    public function testIfMovieDeletionOccuredAnAccessDeniedError(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-6/supprimer');

        $this->assertResponseStatusCodeSame(403);
    }
}
