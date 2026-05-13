<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class GuestController extends AbstractController
{
  #[Route('/admin/guest', name: 'admin_guest_index')]
  public function index(UserRepository $userRepository)
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $guests = $userRepository->getGuest();

    return $this->render('admin/guests/index.html.twig', ['guests' => $guests]);
  }


  #[Route('/admin/guest/add', name: 'admin_guest_add')]
  public function add(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
  {
    $guest = new User();
    $form = $this->createForm(UserType::class, $guest);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $hashedPassword = $passwordHasher->hashPassword(
        $guest,
        $guest->getPassword()
      );

      $guest->setPassword($hashedPassword);

      $entityManager->persist($guest);
      $entityManager->flush();

      return $this->redirectToRoute('admin_guest_index');
    }

    return $this->render('admin/guests/addOrUpdate.html.twig', ['form' => $form->createView()]);
  }


  #[Route('/admin/guest/update/{id}', name: 'admin_guest_update')]
  public function update(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
  {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $hashedPassword = $passwordHasher->hashPassword(
        $user,
        $user->getPassword()
      );

      $user->setPassword($hashedPassword);

      $entityManager->flush();

      return $this->redirectToRoute('admin_guest_index');
    }

    return $this->render('admin/guests/addOrUpdate.html.twig', ['form' => $form->createView()]);
  }

  #[Route('/admin/guest/delete/{id}', name: 'admin_guest_delete')]
  public function delete(User $user, EntityManagerInterface $entityManager, MediaRepository $mediaRepository)
  {

    $medias = $mediaRepository->findBy(['user' => $user]);

    foreach ($medias as $media) {
      if (file_exists($media->getPath())) {
        unlink($media->getPath());
      }

      $entityManager->remove($media);
    }

    $entityManager->remove($user);
    $entityManager->flush();

    return $this->redirectToRoute('admin_guest_index');
  }

  #[Route('/admin/guest/block/{id}', name: 'admin_guest_block')]
  public function block(User $user, EntityManagerInterface $entityManager)
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $user->setIsBlocked(!$user->isBlocked());

    $entityManager->flush();

    $this->addFlash('success', $user->isBlocked() ? 'Invité bloqué' : 'Invité débloqué');

    return $this->redirectToRoute('admin_guest_index');
  }
}
