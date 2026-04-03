<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $data = [
      [2, 'uploads/0051.jpg', 'Titre 0', 1],
      [
        2,
        'uploads/0052.jpg',
        'Titre 1',
        1
      ],
      [
        2,
        'uploads/0053.jpg',
        'Titre 2',
        1
      ],
      [
        2,
        'uploads/0054.jpg',
        'Titre 3',
        2
      ],
      [
        2,
        'uploads/0055.jpg',
        'Titre 4',
        2
      ],
      [
        2,
        'uploads/0056.jpg',
        'Titre 5',
        2
      ],
      [
        2,
        'uploads/0057.jpg',
        'Titre 6',
        3
      ],
      [
        2,
        'uploads/0058.jpg',
        'Titre 7',
        2
      ],
      [
        2,
        'uploads/0059.jpg',
        'Titre 8',
        4
      ],
      [
        2,
        'uploads/0060.jpg',
        'Titre 9',
        3
      ],
      [
        2,
        'uploads/0061.jpg',
        'Titre 10',
        2
      ],
      [
        2,
        'uploads/0062.jpg',
        'Titre 11',
        4
      ],
      [
        2,
        'uploads/0063.jpg',
        'Titre 12',
        1
      ],
      [
        2,
        'uploads/0064.jpg',
        'Titre 13',
        1
      ],
      [
        2,
        'uploads/0065.jpg',
        'Titre 14',
        1
      ],
      [
        2,
        'uploads/0066.jpg',
        'Titre 15',
        3
      ],
      [
        2,
        'uploads/0067.jpg',
        'Titre 16',
        3
      ],
      [
        2,
        'uploads/0068.jpg',
        'Titre 17',
        3
      ],
      [
        2,
        'uploads/0069.jpg',
        'Titre 18',
        3
      ],
      [
        2,
        'uploads/0070.jpg',
        'Titre 19',
        3
      ],
      [
        2,
        'uploads/0071.jpg',
        'Titre 20',
        3
      ],
    ];

    foreach ($data as $item) {
      $media = new Media();
      $user = $manager->getRepository(User::class)->find($item[0]);
      $media->setUser($user);
      $media->setPath($item[1]);
      $media->setTitle($item[2]);
      $album = $manager->getRepository(Album::class)->find($item[3]);
      $media->setAlbum($album);

      $manager->persist($media);
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      AlbumFixtures::class,
    ];
  }
}
