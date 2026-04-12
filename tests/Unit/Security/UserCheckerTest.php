<?php

namespace App\Tests\Unit\Security;

use App\Entity\User;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserCheckerTest extends TestCase
{
  public function testCheckPreAuthThrowsExceptionWhenBlocked(): void
  {
    $checker = new UserChecker();

    $user = new User();
    $user->setEmail('blocked@test.com');
    $user->setName('Blocked');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('pass');
    $user->setIsBlocked(true);

    $this->expectException(CustomUserMessageAccountStatusException::class);
    $this->expectExceptionMessage('Votre compte est bloqué.');

    $checker->checkPreAuth($user);
  }

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

    $this->assertTrue(true);
  }

  public function testCheckPostAuthDoesNotThrow(): void
  {
    $checker = new UserChecker();

    $user = new User();
    $user->setEmail('post@test.com');
    $user->setName('Post');
    $user->setRoles(['ROLE_USER']);
    $user->setPassword('pass');

    $checker->checkPostAuth($user);

    $this->assertTrue(true);
  }
}
