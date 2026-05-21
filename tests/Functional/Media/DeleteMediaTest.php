<?php

namespace App\Tests\Functional\Media;

use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMediaTest extends WebTestCase
{
  public function testDeleteMedia()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();
    $userRepository = $container->get(UserRepository::class);
    $mediaRepository = $container->get(MediaRepository::class);

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $tmpFile = tempnam(sys_get_temp_dir(), 'media_test');
    file_put_contents($tmpFile, 'dummy content');

    $media = new Media();
    $media->setTitle('Media à supprimer');
    $media->setPath($tmpFile);
    $media->setUser($admin);

    $entityManager->persist($media);
    $entityManager->flush();

    $mediaId = $media->getId();

    $countBefore = $mediaRepository->count([]);

    $client->request('POST', '/admin/media/' . $mediaId . '/delete');

    $this->assertResponseRedirects('/admin/media');

    $this->assertSame($countBefore - 1, $mediaRepository->count([]));

    $this->assertNull($mediaRepository->find($mediaId));

    $this->assertFileDoesNotExist($tmpFile);
  }
}
