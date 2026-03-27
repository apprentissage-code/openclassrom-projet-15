<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GuestController extends AbstractController
{
  #[Route('/admin/guest', name: 'admin_guest_index')]
  public function index(EntityManagerInterface $entityManager)
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $guests = $entityManager->getRepository(User::class)->getGuest();

    return $this->render('admin/guests/index.html.twig', ['guests' => $guests]);
  }


  #[Route('/admin/guest/add', name: 'admin_guest_add')]
  public function add(Request $request, EntityManagerInterface $entityManager)
  {
    $guest = new User();
    $form = $this->createForm(UserType::class, $guest);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($guest);
      $entityManager->flush();

      return $this->redirectToRoute('admin_guest_index');
    }

    return $this->render('admin/guests/addOrupdate.html.twig', ['form' => $form->createView()]);
  }


  #[Route('/admin/guest/update/{id}', name: 'admin_guest_update')]
  public function update(User $user, Request $request, EntityManagerInterface $entityManager)
  {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('admin_guest_index');
    }

    return $this->render('admin/guests/addOrupdate.html.twig', ['form' => $form->createView()]);
  }

  #[Route('/admin/guest/delete/{id}', name: 'admin_guest_delete')]
  public function delete(User $user, EntityManagerInterface $entityManager)
  {
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
