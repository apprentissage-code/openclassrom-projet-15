<?php

namespace App\Tests\Functional\Album;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddOrUpdateAlbumTest extends WebTestCase
{
  public function testAddAlbumForm()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $crawler = $client->request('GET', '/admin/album/add');

    $this->assertResponseIsSuccessful();

    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['album[name]'] = 'Album Test';
    $client->submit($form);

    $this->assertResponseRedirects('/admin/album');
  }

  public function testUpdateAlbumForm()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $entityManager = static::getContainer()->get('doctrine')->getManager();

    $album = new \App\Entity\Album();
    $album->setName('Album Original');

    $entityManager->persist($album);
    $entityManager->flush();

    $crawler = $client->request('GET', '/admin/album/update/' . $album->getId());

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Modifier')->form();
    $form['album[name]'] = 'Album Modifié';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/album');
  }
}
