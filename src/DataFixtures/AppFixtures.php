<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Injecte la dépendance UserPasswordHasherInterface pour instancier la classe 'User'
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // 1. Create an Admin so you can access the /admin/userPanel
        $admin = new User();
        $admin->setUserName('admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // 2. Create 20 random users
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUserName($faker->userName());
            $user->setEmail($faker->email());
            $user->setRoles(["ROLE_USER"]);
            
            
            $hashedPassword = $this->passwordHasher->hashPassword($user, '123456');
            
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
