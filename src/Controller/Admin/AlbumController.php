<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AlbumController extends AbstractController
{
    #[Route('/admin/album', name: 'admin_album_index')]
    public function index(EntityManagerInterface $entityManager)
    {
        $albums = $entityManager->getRepository(Album::class)->findAll();

        return $this->render('admin/album/index.html.twig', ['albums' => $albums]);
    }


    #[Route('/admin/album/add', name: 'admin_album_add')]
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/add.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/admin/album/{id}/update', name: 'admin_album_update')]
    public function update(Album $album, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/update.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/album/{id}/delete', name: 'admin_album_delete')]
    public function delete(Album $album, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($album);
        $entityManager->flush();

        return $this->redirectToRoute('admin_album_index');
    }
}
