<?php

namespace App\Tests\Functional\Media;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddMediaTest extends WebTestCase
{
  public function testAddMediaForm()
  {
    $client = static::createClient();

    $admin = static::getContainer()->get(UserRepository::class)
      ->findOneBy(['email' => 'admin@test.com']);

    $client->loginUser($admin);

    $crawler = $client->request('GET', '/admin/media/add');

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
    $form['media[album]'] = '1';
    $form['media[title]'] = 'test upload';
    $form['media[file]'] = $file;

    $client->submit($form);

    $this->assertResponseRedirects('/admin/media');
  }
}
