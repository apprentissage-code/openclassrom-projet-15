<?php

namespace App\Tests\Functional\Album;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteAlbumTest extends WebTestCase
{
  public function testDeleteAlbum()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $entityManager = static::getContainer()->get('doctrine')->getManager();

    $album = new Album();
    $album->setName('Album à supprimer');

    $entityManager->persist($album);
    $entityManager->flush();

    $albumID = $album->getId();

    $client->request('GET', '/admin/album/delete/' . $albumID);

    $this->assertResponseRedirects('/admin/album');

    $deletedAlbum = static::getContainer()->get(AlbumRepository::class)->find($albumID);
    $this->assertNull($deletedAlbum);

  }
}
