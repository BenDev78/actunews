<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * Le Manager est un objet qui sait manipuler en BDD
     * nos entités. (Insert, Update, Delete)
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        #Création des catégories
        $politique = new Category();
        $politique->setName('Politique')->setAlias('politique');

        $economie = new Category();
        $economie->setName('Economie')->setAlias('economie');

        $social = new Category();
        $social->setName('Social')->setAlias('social');

        $sante = new Category();
        $sante->setName('Sante')->setAlias('sante');

        $culture = new Category();
        $culture->setName('Culture')->setAlias('culture');

        $loisirs = new Category();
        $loisirs->setName('Loisirs')->setAlias('loisirs');

        $sports = new Category();
        $sports->setName('Sports')->setAlias('sports');

        $manager->persist($politique);
        $manager->persist($economie);
        $manager->persist($social);
        $manager->persist($sante);
        $manager->persist($culture);
        $manager->persist($loisirs);
        $manager->persist($sports);

        $manager->flush();

        #Création d'un user
        $user = new User();
        $user->setFirstname('Benjamin')
            ->setLastname('Leson-larivée')
            ->setEmail('test@gmail.com')
            ->setPassword('test')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTime());
        $manager->persist($user);
        $manager->flush();

        #Création des articles | Politique
        for ($i = 0; $i < 3; $i++) {
            $post = new Post();
            $post->setTitle('Lorem ipsum dolor' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A atque est fugit iure officiis quas, ut. Aperiam aspernatur commodi deserunt eaque enim ipsa laudantium sed sint, temporibus, unde veritatis voluptates.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setCategory($politique)
                ->setUser($user);

            $manager->persist($post);
            $manager->flush();
        }

        #Création des articles | Sante
        for ($i = 3; $i < 7; $i++) {
            $post = new Post();
            $post->setTitle('Lorem ipsum dolor' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A atque est fugit iure officiis quas, ut. Aperiam aspernatur commodi deserunt eaque enim ipsa laudantium sed sint, temporibus, unde veritatis voluptates.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setCategory($sante)
                ->setUser($user);

            $manager->persist($post);
            $manager->flush();
        }

        #Création des articles | Sports
        for ($i = 7; $i < 11; $i++) {
            $post = new Post();
            $post->setTitle('Lorem ipsum dolor' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A atque est fugit iure officiis quas, ut. Aperiam aspernatur commodi deserunt eaque enim ipsa laudantium sed sint, temporibus, unde veritatis voluptates.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setCategory($sports)
                ->setUser($user);

            $manager->persist($post);
            $manager->flush();
        }

        #Création des articles | Culture
        for ($i = 11; $i < 14; $i++) {
            $post = new Post();
            $post->setTitle('Lorem ipsum dolor' . $i)
                ->setAlias('lorem-ipsum-dolor-' . $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A atque est fugit iure officiis quas, ut. Aperiam aspernatur commodi deserunt eaque enim ipsa laudantium sed sint, temporibus, unde veritatis voluptates.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setCategory($culture)
                ->setUser($user);

            $manager->persist($post);
            $manager->flush();
        }
    }
}
