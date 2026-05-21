<?php

namespace App\Tests\Functional\Album;

use App\Entity\Album;
use App\Repository\AlbumRepository;
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

    $container = static::getContainer();
    $albumRepository = $container->get(AlbumRepository::class);

    $countBefore = $albumRepository->count([]);

    $crawler = $client->request('GET', '/admin/album/new');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['album[name]'] = 'Album Test';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/album');

    $countAfter = $albumRepository->count([]);

    $this->assertSame($countBefore + 1, $countAfter);
  }

  public function testUpdateAlbumForm()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();
    $albumRepository = $container->get(AlbumRepository::class);

    $album = new Album();
    $album->setName('Album Original');

    $entityManager->persist($album);
    $entityManager->flush();

    $crawler = $client->request('GET', '/admin/album/' . $album->getId() . '/update');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Modifier')->form();
    $form['album[name]'] = 'Album Modifié';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/album');

    $updatedAlbum = $albumRepository->find($album->getId());

    $this->assertSame('Album Modifié', $updatedAlbum->getName());
  }
}
