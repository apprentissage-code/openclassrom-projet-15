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

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $crawler = $client->request('GET', '/admin/guest/add');

    $this->assertResponseIsSuccessful();

    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['user[name]'] = 'test name';
    $form['user[email]'] = 'test@email.fr';
    $form['user[description]'] = 'test description';
    $form['user[password]'] = 'passwordtest';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/guest');
  }

  public function testUpdateGuest()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $entityManager = static::getContainer()->get('doctrine')->getManager();

    $user = new User();
    $user->setEmail('guest_update@test.com');
    $user->setName('Guest');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('plainpass');

    $entityManager->persist($user);
    $entityManager->flush();

    $crawler = $client->request('GET', '/admin/guest/' . $user->getId() . 'update/');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorExists('form');

    $form = $crawler->selectButton('Ajouter')->form();

    $form['user[email]'] = 'updated@test.com';
    $form['user[name]'] = 'Updated Name';
    $form['user[password]'] = 'newpassword';

    $client->submit($form);

    $this->assertResponseRedirects('/admin/guest');

    $updated = $entityManager->getRepository(User::class)
      ->find($user->getId());

    $this->assertSame('updated@test.com', $updated->getEmail());
    $this->assertSame('Updated Name', $updated->getName());
  }
}
