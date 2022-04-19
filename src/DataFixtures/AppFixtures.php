<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // dd($faker);

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->name);
            $manager->persist($category);

            for($j = 0; $j < mt_rand(1,10); $j++)
            {
                $subCategory = new SubCategory();
                $subCategory->setName($faker->name);
                $subCategory->setCategory($category);
                $manager->persist($subCategory);
            }
        }


        // je créé 20 personnes
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword("$2y$13$..gBQnay9Q7Jo8D1Tulpy.QuR1daaPaesKnaAXwT.toaSSQhs5/jG");
            $user->setIsVerified(true);
            $manager->persist($user);
            for($j = 0; $j < 1; $j++)
            {
                $profile = new Profile();
                $profile->setUserName($faker->userName);
                $profile->setFirstName($faker->firstName);
                $profile->setLastName($faker->lastName);
                $profile->setStreet("5, place de l'église");
                $profile->setZipcode("78490");
                $profile->setCity("galluis");
                $profile->setDepartment("Yvelines");
                $profile->setRegion("Île-de-France");
                $profile->setLat("48.7716");
                $profile->setLng("2.0699");
                $profile->setGender($faker->title);
                $manager->persist($profile);
            }            
        }   

        // for ($i = 0; $i < mt_rand(1, 5); $i++) {
        //     $announce = new Announcement();
        //     $announce->setTitle($faker->title);
        //     $announce->setDescription($faker->text);
        //     $announce->setZipcode("78490");
        //     $announce->setDepartment("Yvelines");
        //     $announce->setRegion("Île-de-France");
        //     $announce->setLat("48.7716");
        //     $announce->setLng("2.0699");
        //     $announce->setSubCategory($subCategory);
        //     $announce->setProfile($profile);
        //     $manager->persist($announce);
            
        // }

        $manager->flush();
    }
}
