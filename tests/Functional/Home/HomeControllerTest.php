<?php

namespace App\Tests\Functional\Home;

use App\Entity\Album;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testGuestsPage()
    {
        $client = static::createClient();

        $client->request('GET', '/guests');

        $this->assertResponseIsSuccessful();
    }

    public function testGuestPage()
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $guest = new User();
        $guest->setEmail('guest@test.com');
        $guest->setName('Guest');
        $guest->setRoles(['ROLE_USER']);
        $guest->setPassword('dummy');

        $entityManager->persist($guest);
        $entityManager->flush();

        $client->request('GET', '/guests/' . $guest->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testPortfolioPageWithoutAlbum()
    {
        $client = static::createClient();

        $client->request('GET', '/portfolio');

        $this->assertResponseIsSuccessful();
    }

    public function testPortfolioPageWithAlbum()
    {
        $client = static::createClient();

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $album = new Album();
        $album->setName('Test album');

        $entityManager->persist($album);
        $entityManager->flush();

        $client->request('GET', '/portfolio/' . $album->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testAboutPage()
    {
        $client = static::createClient();

        $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
    }
}
