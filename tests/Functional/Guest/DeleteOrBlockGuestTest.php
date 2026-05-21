<?php

namespace App\Tests\Functional\Guest;

use App\Entity\Media;
use App\Entity\User;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteOrBlockGuestTest extends WebTestCase
{
  public function testDeleteUserAndMedias()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();
    $userRepository = $container->get(UserRepository::class);
    $mediaRepository = $container->get(MediaRepository::class);

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $user = new User();
    $user->setEmail('testuser@example.com');
    $user->setName('testuser');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('dummy');

    $entityManager->persist($user);
    $entityManager->flush();

    $tmpFile = tempnam(sys_get_temp_dir(), 'media_test');
    file_put_contents($tmpFile, 'dummy content');

    $media = new Media();
    $media->setTitle('Media du user');
    $media->setPath($tmpFile);
    $media->setUser($user);

    $entityManager->persist($media);
    $entityManager->flush();

    $userId = $user->getId();

    $countUsersBefore = $userRepository->count([]);
    $countMediaBefore = $mediaRepository->count([]);

    $client->request('POST', '/admin/guest/' . $userId . '/delete');

    $this->assertResponseRedirects('/admin/guest');

    $this->assertSame($countUsersBefore - 1, $userRepository->count([]));
    $this->assertSame($countMediaBefore - 1, $mediaRepository->count([]));

    $this->assertNull($userRepository->find($userId));
  }

  public function testBlockAndUnblockGuest()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();
    $userRepository = $container->get(UserRepository::class);

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $user = new User();
    $user->setEmail('block@test.com');
    $user->setName('BlockUser');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('test');

    $entityManager->persist($user);
    $entityManager->flush();

    $id = $user->getId();

    $crawler = $client->request('GET', '/admin/guest');

    $this->assertResponseIsSuccessful();

    $this->assertStringContainsString(
      'Bloquer',
      $client->getResponse()->getContent()
    );

    $client->request('POST', '/admin/guest/' . $id . '/block');

    $this->assertResponseRedirects('/admin/guest');

    $crawler = $client->followRedirect();

    $this->assertStringContainsString(
      'Débloquer',
      $client->getResponse()->getContent()
    );

    $client->request('POST', '/admin/guest/' . $id . '/block');

    $this->assertResponseRedirects('/admin/guest');

    $crawler = $client->followRedirect();

    $this->assertStringContainsString(
      'Bloquer',
      $client->getResponse()->getContent()
    );
  }
}
