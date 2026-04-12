<?php

namespace App\Tests\Functional\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageAdminTest extends WebTestCase
{
  public function testAdminAlbumPage()
  {

    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $client->request('GET', '/admin/album');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('table');
    $this->assertSelectorTextContains('main h1', 'Albums');
  }

  public function testAdminGuestPage()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $client->request('GET', '/admin/guest');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('table');
    $this->assertSelectorTextContains('main h1', 'Invités');
  }

  public function testAdminMediaPage()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $client->request('GET', '/admin/media');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('table');
    $this->assertSelectorTextContains('main h1', 'Medias');
  }

}
