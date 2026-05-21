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

    $container = static::getContainer();
    $userRepository = $container->get(UserRepository::class);
    $albumRepository = $container->get(AlbumRepository::class);
    $entityManager = $container->get('doctrine')->getManager();

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $album = new Album();
    $album->setName('Album à supprimer');

    $entityManager->persist($album);
    $entityManager->flush();

    $albumId = $album->getId();

    $countBefore = $albumRepository->count([]);

    $client->request('POST', '/admin/album/' . $albumId . '/delete');

    $this->assertResponseRedirects('/admin/album');

    $countAfter = $albumRepository->count([]);

    $this->assertSame($countBefore - 1, $countAfter);

    $this->assertNull($albumRepository->find($albumId));
  }
}
