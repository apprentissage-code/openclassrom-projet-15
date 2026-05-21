<?php

namespace App\Tests\Functional\Media;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddMediaTest extends WebTestCase
{
  public function testAddMediaForm()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $userRepository = $container->get(UserRepository::class);
    $albumRepository = $container->get(AlbumRepository::class);
    $mediaRepository = $container->get(MediaRepository::class);
    $entityManager = $container->get('doctrine')->getManager();

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $album = new Album();
    $album->setName('Test Album');

    $entityManager->persist($album);
    $entityManager->flush();

    $countBefore = $mediaRepository->count([]);

    $crawler = $client->request('GET', '/admin/media/new');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $file = new UploadedFile(
      __DIR__ . '/fixtures/test.jpg',
      'test.jpg',
      'image/jpeg',
      null,
      true
    );

    $form = $crawler->selectButton('Ajouter')->form();

    $client->submit($form, [
      'media[album]' => $album->getId(),
      'media[title]' => 'test upload',
      'media[file]' => $file,
    ]);

    $this->assertResponseRedirects('/admin/media');

    $countAfter = $mediaRepository->count([]);

    $this->assertSame($countBefore + 1, $countAfter);
  }
}
