<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $admin = new User();
    $admin->setName('Admin');
    $admin->setEmail('admin@test.com');
    $admin->setDescription('Utilisateur administrateur');
    $admin->setAdmin(true);

    $manager->persist($admin);

    for ($i = 1; $i <= 5; $i++) {
      $user = new User();
      $user->setName('User ' . $i);
      $user->setEmail('user' . $i . '@test.com');
      $user->setDescription('Description du user ' . $i);
      $user->setAdmin(false);

      $manager->persist($user);
    }

    $manager->flush();
  }
}
