<?php

namespace App\Tests\Api;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TelegramUser;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class TelegramUserTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/telegram_users');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/contexts/TelegramUser',
            '@id' => '/telegram_users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 31,
            'hydra:view' => [
                '@id' => '/telegram_users?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/telegram_users?page=1',
                'hydra:last' => '/telegram_users?page=2',
                'hydra:next' => '/telegram_users?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(TelegramUser::class);
    }

    public function testCreateBook(): void
    {
        $response = static::createClient()->request('POST', '/telegram_users', ['json' => [
            "telegramId" => 123456789,
            "username" => "test_username",
            "fullName" => "Test Full Name"
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/TelegramUser',
            '@type' => 'TelegramUser',
            "telegramId" => 123456789,
            "username" => "test_username",
            "fullName" => "Test Full Name",
            "balance" => 0,
            "isManager" => False,
            "isAdmin" => False
        ]);
        $this->assertMatchesRegularExpression('~^/telegram_users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(TelegramUser::class);
    }

    public function testCreateInvalidBook(): void
    {
        static::createClient()->request('POST', '/telegram_users', ['json' => [
            'telegramId' => 'invalid'
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Error',
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'The type of the "telegramId" attribute must be "int", "string" given.',
        ]);
    }

    public function testUpdateBook(): void
    {
        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        // telegramId 111222333 has been generated by Alice when loading test fixtures.
        // Because Alice use a seeded pseudo-random number generator, we're sure that this telegramId will always be generated.
        $iri = $this->findIriBy(TelegramUser::class, ['telegramId' => 111222333]);
        $client->request('PUT', $iri, ['json' => [
            'username' => 'updated username',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'telegramId' => 111222333,
            'username' => 'updated username',
        ]);
    }

    public function testDeleteBook(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(TelegramUser::class, ['telegramId' => 111222333]);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(TelegramUser::class)->findOneBy(['telegramId' => 111222333])
        );
    }
}