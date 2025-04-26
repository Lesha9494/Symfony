<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $itDepartment = new Department();
        $itDepartment->setName('IT');
        $manager->persist($itDepartment);

        $hrDepartment = new Department();
        $hrDepartment->setName('HR');
        $manager->persist($hrDepartment);

        $salesDepartment = new Department();
        $salesDepartment->setName('Sales');
        $manager->persist($salesDepartment);

        $user1 = new User();
        $user1->setFirstName('Иван');
        $user1->setLastName('Иванов');
        $user1->setEmail('ivanov@mail.com');
        $user1->setAge(30);
        $user1->setStatus('Aктивныйtive');
        $user1->setTelegram('ivanov123');
        $user1->setAddress('Москва');
        $user1->setDepartment($itDepartment);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName('Мария');
        $user2->setLastName('Петрова');
        $user2->setEmail('petrova@mail.com');
        $user2->setAge(25);
        $user2->setStatus('Активный');
        $user2->setTelegram('petrova321');
        $user2->setAddress('Питер');
        $user2->setDepartment($hrDepartment);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstName('Алексей');
        $user3->setLastName('Смирнов');
        $user3->setEmail('smirnov@mail.com');
        $user3->setAge(40);
        $user3->setStatus('Активный');
        $user3->setTelegram('smirnov456');
        $user3->setAddress('Новосибирск');
        $user3->setDepartment($salesDepartment);
        $manager->persist($user3);

        $manager->flush();
    }
}
