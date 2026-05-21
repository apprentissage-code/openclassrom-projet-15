<?php

namespace App\Tests\Functional\Guest;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddOrUpdateGuestTest extends WebTestCase
{
  public function testAddGuestForm()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $userRepository = $container->get(UserRepository::class);

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $countBefore = $userRepository->count([]);

    $crawler = $client->request('GET', '/admin/guest/new');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['user[name]'] = 'test name';
    $form['user[email]'] = 'test@email.fr';
    $form['user[description]'] = 'test description';
    $form['user[password]'] = 'passwordtest';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/guest');

    $countAfter = $userRepository->count([]);

    $this->assertSame($countBefore + 1, $countAfter);
  }

  public function testUpdateGuest()
  {
    $client = static::createClient();

    $container = static::getContainer();
    $entityManager = $container->get('doctrine')->getManager();
    $userRepository = $container->get(UserRepository::class);

    $admin = $userRepository->findOneBy(['email' => 'admin@test.com']);
    $client->loginUser($admin);

    $user = new User();
    $user->setEmail('guest_update@test.com');
    $user->setName('Guest');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('plainpass');

    $entityManager->persist($user);
    $entityManager->flush();

    $crawler = $client->request('GET', '/admin/guest/' . $user->getId() . '/update');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['user[email]'] = 'updated@test.com';
    $form['user[name]'] = 'Updated Name';
    $form['user[password]'] = 'newpassword';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/guest');

    $updatedUser = $userRepository->find($user->getId());

    $this->assertSame('updated@test.com', $updatedUser->getEmail());
    $this->assertSame('Updated Name', $updatedUser->getName());
  }
}
