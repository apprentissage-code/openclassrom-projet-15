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

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $entityManager = static::getContainer()->get('doctrine')->getManager();

    $tmpFile = tempnam(sys_get_temp_dir(), 'media_test');
    file_put_contents($tmpFile, 'dummy content');

    $media = new Media();
    $media->setTitle('Media à supprimer');
    $media->setPath($tmpFile);
    $media->setUser($admin);

    $entityManager->persist($media);
    $entityManager->flush();

    $mediaId = $media->getId();

    $client->request('GET', '/admin/media/delete/' . $mediaId);

    $this->assertResponseRedirects('/admin/media');

    $deletedMedia = static::getContainer()->get(MediaRepository::class)->find($mediaId);
    $this->assertNull($deletedMedia);

    $this->assertFileDoesNotExist($tmpFile);
  }
}
