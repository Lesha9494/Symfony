<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/user', name: 'index_user')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');

        $qb = $userRepository->createQueryBuilder('u');
        if ($query) {
            $qb->andWhere('u.lastName LIKE :query OR u.firstName LIKE :query OR u.email LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('/user/index.html.twig', ['users' => $users]);
    }

    #[Route('/user/create', name: 'create_user', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setLastName($request->request->get('last_name'));
            $user->setFirstName($request->request->get('first_name'));
            $user->setAge($request->request->get('age'));
            $user->setStatus($request->request->get('status'));
            $user->setEmail($request->request->get('email'));
            $user->setTelegram($request->request->get('telegram'));
            $user->setAddress($request->request->get('address'));

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('index_user');
        }

        return $this->render('user/create.html.twig');
    }

    #[Route('/user/{user}', name: 'edit_user', methods: ['GET'])]
    public function edit(User $user): Response
    {
        return $this->render('user/edit.html.twig', ['user' => $user]);
    }

    #[Route('/user/{user}', name: 'update_user', methods: ['PUT'])]
    public function update(User $user, Request $request): Response
    {
        $user->setLastName($request->request->get('last_name', $user->getLastName()));
        $user->setFirstName($request->request->get('first_name', $user->getFirstName()));
        $user->setAge($request->request->get('age', $user->getAge()));
        $user->setStatus($request->request->get('status', $user->getStatus()));
        $user->setEmail($request->request->get('email', $user->getEmail()));
        $user->setTelegram($request->request->get('telegram', $user->getTelegram()));
        $user->setAddress($request->request->get('address', $user->getAddress()));

        $this->em->flush();

        return $this->redirectToRoute('index_user');
    }

    #[Route('/user/{user}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
    
        return $this->redirectToRoute('index_user');
    }
    
}
