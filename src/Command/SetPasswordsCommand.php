<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
  name: 'app:set-passwords',
  description: 'Add a short description for your command',
)]
class SetPasswordsCommand extends Command
{
  private EntityManagerInterface $entityManager;
  private UserPasswordHasherInterface $hasher;

  public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
  {
    parent::__construct();

    $this->hasher = $hasher;
    $this->entityManager = $entityManager;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $users = $this->entityManager->getRepository(User::class)->findAll();

    foreach ($users as $user) {
      $user->setPassword(
        $this->hasher->hashPassword($user, 'password')
      );

      if (!$user->getRoles()) {
        $user->setRoles(['ROLE_USER']);
      }
    }

    $this->entityManager->flush();

    return Command::SUCCESS;
  }
}
