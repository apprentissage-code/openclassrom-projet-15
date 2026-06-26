<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/media', name: 'admin_media_')]
class MediaController extends AbstractController
{
  #[Route('', name: 'index', methods: ['GET'])]
  public function index(Request $request, MediaRepository $mediaRepository)
  {
    $page = $request->query->getInt('page', 1);

    $criteria = [];

    if (!$this->isGranted('ROLE_ADMIN')) {
      $criteria['user'] = $this->getUser();
    }

    $medias = $mediaRepository->findBy(
      $criteria,
      ['id' => 'ASC'],
      25,
      25 * ($page - 1)
    );

    $total = $mediaRepository->count($criteria);

    return $this->render('admin/media/index.html.twig', [
      'medias' => $medias,
      'total' => $total,
      'page' => $page
    ]);
  }

  #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
  public function add(Request $request, EntityManagerInterface $entityManager)
  {
    $media = new Media();
    $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (!$this->isGranted('ROLE_ADMIN') || $media->getUser() === null) {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $media->setUser($user);
      }

      $file = $media->getFile();

      if ($file) {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move('uploads/', $filename);

        $media->setPath('uploads/' . $filename);
      }

      $entityManager->persist($media);
      $entityManager->flush();

      return $this->redirectToRoute('admin_media_index');
    }

    return $this->render('admin/media/add.html.twig', ['form' => $form->createView()]);
  }

  #[Route('/{id}/delete', name: 'delete')]
  public function delete(Media $media, EntityManagerInterface $entityManager)
  {
    $entityManager->remove($media);
    $entityManager->flush();
    unlink($media->getPath());

    return $this->redirectToRoute('admin_media_index');
  }
}
