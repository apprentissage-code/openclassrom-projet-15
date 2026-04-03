<?php

namespace App\Tests\Functional\Album;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddAlbumTest extends WebTestCase
{
    public function testAddAlbumForm()
    {
        $client = static::createClient();

        $admin = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'admin@test.com']);

        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/album/add');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['album[name]'] = 'Album Test';
        $client->submit($form);

        $this->assertResponseRedirects('/admin/album');
    }
}
