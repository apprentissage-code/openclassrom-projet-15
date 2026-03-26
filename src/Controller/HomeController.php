<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/guests', name: 'guests')]
    public function guests(EntityManagerInterface $entityManager)
    {
        $guests = $entityManager->getRepository(User::class)->findBy(['admin' => false]);
        return $this->render('front/guests.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route('/guests/{id}', name: 'guest')]
    public function guest(User $guest)
    {
        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }

    #[Route('/portfolio/{id?}', name: 'portfolio')]
    public function portfolio( EntityManagerInterface $entityManager, ?Album $album = null)
    {
        $albums = $entityManager->getRepository(Album::class)->findAll();
        $user = $entityManager->getRepository(User::class)->findOneByAdmin(true);

        $medias = $album
            ? $entityManager->getRepository(Media::class)->findByAlbum($album)
            : $entityManager->getRepository(Media::class)->findByUser($user);
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about()
    {
        return $this->render('front/about.html.twig');
    }
}
