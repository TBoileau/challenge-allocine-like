<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostReviewTest extends WebTestCase
{
    public function testIfPostingReviewOccuredAnForbiddenError(): void
    {
        $client = static::createClient();

        $client->request('GET', '/films/titre-1');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testIfPostingReviewWorks(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-1');

        $client->submitForm('Poster', [
            'review[title]' => 'Review',
            'review[content]' => 'Content',
            'review[rating]' => 5
        ]);

        $this->assertResponseStatusCodeSame(302);

        $reviewRepository = $client->getContainer()->get(ReviewRepository::class);

        $this->assertEquals(251, $reviewRepository->count([]));

        $review = $reviewRepository->findOneBy(['title' => 'Review']);

        $this->assertNotNull($review);

        $client->followRedirect();

        $this->assertRouteSame('movie_show');
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function testIfPostingReviewFailsDueToInvalidData(array $formData): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('GET', '/films/titre-1');

        $client->submitForm('Poster', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideInvalidData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'review[title]' => 'Review',
                'review[content]' => 'Content',
                'review[rating]' => 5
            ];

        yield 'title is empty' => [$baseData(['review[title]' => ''])];
        yield 'content is empty' => [$baseData(['review[content]' => ''])];
    }
}
