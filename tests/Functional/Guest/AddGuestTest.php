<?php

namespace App\Tests\Functional\Guest;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddGuestTest extends WebTestCase
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
}
