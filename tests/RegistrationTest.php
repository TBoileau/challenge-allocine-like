<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationTest extends WebTestCase
{
    public function testIfRegistrationWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/inscription');

        $client->submitForm('S\'inscrire', [
            'registration[email]' => 'user+11@email.com',
            'registration[plainPassword]' => 'password',
            'registration[nickname]' => 'user+11',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $this->assertEquals(11, $userRepository->count([]));

        $user = $userRepository->findOneBy(['email' => 'user+11@email.com']);

        $this->assertNotNull($user);

        $client->request('GET', '/connexion');

        $client->submitForm('Se connecter', [
            'email' => 'user+11@email.com',
            'password' => 'password',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('home');
    }

    /**
     * @param array{email: string, password: string, nickname: string} $formData
     *
     * @dataProvider provideInvalidData
     */
    public function testIfRegistrationFailsDueToInvalidData(array $formData): void
    {
        $client = static::createClient();

        $client->request('GET', '/inscription');

        $client->submitForm('S\'inscrire', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideInvalidData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'registration[email]' => 'user+11@email.com',
                'registration[plainPassword]' => 'password',
                'registration[nickname]' => 'user+11',
            ];

        yield 'email is empty' => [$baseData(['registration[email]' => ''])];
        yield 'password is empty' => [$baseData(['registration[plainPassword]' => ''])];
        yield 'nickname is empty' => [$baseData(['registration[nickname]' => ''])];
        yield 'email is invalid' => [$baseData(['registration[email]' => 'fail'])];
        yield 'email is not unique' => [$baseData(['registration[email]' => 'user+1@email.com'])];
        yield 'nickname is not unique' => [$baseData(['registration[nickname]' => 'user+1'])];
    }
}
