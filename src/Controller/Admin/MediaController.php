<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
  #[Route('/admin/media', name: 'admin_media_index')]
  public function index(Request $request, EntityManagerInterface $entityManager)
  {
    $page = $request->query->getInt('page', 1);

    $criteria = [];

    $criteria['user'] = $this->getUser();

    $medias = $entityManager->getRepository(Media::class)->findBy(
      $criteria,
      ['id' => 'ASC'],
      25,
      25 * ($page - 1)
    );
    $total = $entityManager->getRepository(Media::class)->count([]);

    return $this->render('admin/media/index.html.twig', [
      'medias' => $medias,
      'total' => $total,
      'page' => $page
    ]);
  }

  #[Route('/admin/media/add', name: 'admin_media_add')]
  public function add(Request $request, EntityManagerInterface $entityManager)
  {
    $media = new Media();
    $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $media->setUser($this->getUser());

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

  #[Route('/admin/media/delete/{id}', name: 'admin_media_delete')]
  public function delete(Media $media, EntityManagerInterface $entityManager)
  {
    $entityManager->remove($media);
    $entityManager->flush();
    unlink($media->getPath());

    return $this->redirectToRoute('admin_media_index');
  }
}
