<?php

namespace App\Tests\Unit\Security;

use App\Entity\User;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;

class UserCheckerTest extends TestCase
{
  public function testCheckPreAuthDoesNothingWhenNotBlocked(): void
  {
    $checker = new UserChecker();

    $user = new User();
    $user->setEmail('ok@test.com');
    $user->setName('Ok');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('pass');
    $user->setIsBlocked(false);

    $checker->checkPreAuth($user);

  }
}
