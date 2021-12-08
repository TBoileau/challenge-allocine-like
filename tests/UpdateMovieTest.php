<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UpdateMovieTest extends WebTestCase
{
    use UploadImageTrait;

    public function testIfMovieEditOccuredAnForbiddenError(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films/titre-6/modifier');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testIfMovieEditWorks(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-1/modifier');

        $client->submitForm('Modifier', [
            'movie[title]' => 'Titre',
            'movie[synopsis]' => 'Synopsis',
            'movie[duration]' => 120,
            'movie[releaseDate]' => 2021,
            'movie[actors]' => [1, 2],
            'movie[directors]' => [3, 4],
            'movie[image]' => $this->createImage(),
        ]);

        $this->assertResponseStatusCodeSame(302);

        $movieRepository = $client->getContainer()->get(MovieRepository::class);

        $this->assertEquals(51, $movieRepository->count([]));

        $movie = $movieRepository->findOneBy(['title' => 'Titre']);

        $this->assertNotNull($movie);

        $client->followRedirect();

        $this->assertRouteSame('movie_show');
    }

    public function testIfMovieEditOccuredAnAccessDeniedError(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-6/modifier');

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function testIfMovieEditFailsDueToInvalidData(array $formData): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-1/modifier');

        $client->submitForm('Modifier', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideInvalidData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'movie[title]' => 'Titre',
                'movie[synopsis]' => 'Synopsis',
                'movie[duration]' => 120,
                'movie[releaseDate]' => 2021,
                'movie[actors]' => [1, 2],
                'movie[directors]' => [3, 4],
                'movie[image]' => $this->createImage(),
            ];

        yield 'title is empty' => [$baseData(['movie[title]' => ''])];
        yield 'synopsis is empty' => [$baseData(['movie[synopsis]' => ''])];
        yield 'synopsis must contains at least 10 characters' => [$baseData(['movie[synopsis]' => 'fail'])];
        yield 'duration is empty' => [$baseData(['movie[duration]' => ''])];
        yield 'releaseDate is empty' => [$baseData(['movie[releaseDate]' => ''])];
        yield 'no actor' => [$baseData(['movie[actors]' => []])];
        yield 'no director' => [$baseData(['movie[directors]' => []])];
        yield 'image is null' => [$baseData(['movie[image]' => null])];
    }
}
