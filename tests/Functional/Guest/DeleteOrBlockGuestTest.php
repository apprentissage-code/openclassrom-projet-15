<?php

namespace App\Tests\Functional\Guest;

use App\Entity\User;
use App\Entity\Media;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteOrBlockGuestTest extends WebTestCase
{
  public function testDeleteUserAndMedias()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();

    $admin = $container->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $user = new User();
    $user->setEmail('testuser@example.com');
    $user->setName('testuser');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('dummy');

    $entityManager->persist($user);
    $entityManager->flush();

    $userId = $user->getId();

    $tmpFile = tempnam(sys_get_temp_dir(), 'media_test');
    file_put_contents($tmpFile, 'dummy content');

    $media = new Media();
    $media->setTitle('Media du user');
    $media->setPath($tmpFile);

    $media->setUser($user);

    $entityManager->persist($media);
    $entityManager->flush();

    $mediaId = $media->getId();

    $client->request('GET', '/admin/guest/delete/' . $userId);

    $this->assertResponseRedirects('/admin/guest');

    $entityManager->clear();

    $deletedUser = $container->get(UserRepository::class)->find($userId);
    $this->assertNull($deletedUser);

    $deletedMedia = $container->get(MediaRepository::class)->find($mediaId);
    $this->assertNull($deletedMedia);

    $this->assertFileDoesNotExist($tmpFile);
  }

  public function testBlockAndUnblockGuest()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $entityManager = static::getContainer()->get('doctrine')->getManager();

    $user = new \App\Entity\User();
    $user->setEmail('block@test.com');
    $user->setName('BlockUser');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('test');

    $entityManager->persist($user);
    $entityManager->flush();

    $id = $user->getId();

    $client->request('GET', '/admin/guest/block/' . $id);

    $this->assertResponseRedirects('/admin/guest');

    $blocked = $entityManager->getRepository(\App\Entity\User::class)->find($id);
    $this->assertTrue($blocked->isBlocked());

    $client->request('GET', '/admin/guest/block/' . $id);

    $unblocked = $entityManager->getRepository(\App\Entity\User::class)->find($id);
    $this->assertFalse($unblocked->isBlocked());
  }
}
