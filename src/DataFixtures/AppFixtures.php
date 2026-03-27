<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }

  public function load(ObjectManager $manager): void
  {
    $admin = new User();
    $admin->setName('Admin');
    $admin->setEmail('admin@test.com');
    $admin->setDescription('Utilisateur administrateur');
    $admin->setRoles(['ROLE_ADMIN']);
    $admin->setPassword(
      $this->hasher->hashPassword($admin, 'password')
    );

    $manager->persist($admin);

    for ($i = 1; $i <= 5; $i++) {
      $user = new User();
      $user->setName('User ' . $i);
      $user->setEmail('user' . $i . '@test.com');
      $user->setDescription('Description du user ' . $i);
      $user->setRoles(['ROLE_USER']);
      $user->setPassword(
        $this->hasher->hashPassword($user, 'password')
      );

      $manager->persist($user);
    }

    $manager->flush();
  }
}
