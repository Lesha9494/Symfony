<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/user', name: 'index_user')]
    public function index(Request $request, UserRepository $userRepository): Response
    {   
        $query = $request->query->get('q');

        $qb = $userRepository->createQueryBuilder('u')
        ->leftJoin('u.department', 'd')
        ->addSelect('d');

        if ($query) {
            $qb->andWhere(
                'u.lastName LIKE :query OR u.firstName LIKE :query OR u.email LIKE :query OR d.name LIKE :query'
            )
            ->setParameter('query', '%' . $query . '%');
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('/user/index.html.twig', ['users' => $users]);
    }

    #[Route('/user/create', name: 'create_user', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        DepartmentRepository $departmentRepository,
        ValidatorInterface $validator
    ): Response {
        $departments = $departmentRepository->findAll();
        $user = new User();
    
        if ($request->isMethod('POST')) {
            $user->setLastName($request->request->get('last_name'));
            $user->setFirstName($request->request->get('first_name'));
            $user->setAge((int) $request->request->get('age'));
            $user->setStatus($request->request->get('status'));
            $user->setEmail($request->request->get('email'));
            $user->setTelegram($request->request->get('telegram'));
            $user->setAddress($request->request->get('address'));
    
            $departmentId = $request->request->get('department');
            if ($departmentId) {
                $department = $departmentRepository->find($departmentId);
                $user->setDepartment($department);
            }
    
            $image = $request->files->get('image');
            if ($image) {
                $filename = uniqid() . '.' . $image->guessExtension();
                $image->move('uploads/avatars', $filename);
                $user->setAvatar($filename);
            }
    
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->render('user/create.html.twig', [
                    'departments' => $departments,
                    'user' => $user,
                    'errors' => $errors,
                ]);                
            }
    
            $this->em->persist($user);
            $this->em->flush();
    
            return $this->redirectToRoute('index_user');
        }
    
        return $this->render('user/create.html.twig', [
            'departments' => $departments,
            'user' => $user,
            'errors' => null,
        ]);
    }
    

    #[Route('/user/{user}', name: 'edit_user', methods: ['GET'])]
    public function edit(User $user, DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'departments' => $departments,
        ]);
    }
    

    #[Route('/user/{user}', name: 'update_user', methods: ['PUT'])]
    public function update(User $user, Request $request, DepartmentRepository $departmentRepository): Response
    {
        $user->setLastName($request->request->get('last_name', $user->getLastName()));
        $user->setFirstName($request->request->get('first_name', $user->getFirstName()));
        $user->setAge($request->request->get('age', $user->getAge()));
        $user->setStatus($request->request->get('status', $user->getStatus()));
        $user->setEmail($request->request->get('email', $user->getEmail()));
        $user->setTelegram($request->request->get('telegram', $user->getTelegram()));
        $user->setAddress($request->request->get('address', $user->getAddress()));
    
        $departmentId = $request->request->get('department');
        $department = $departmentRepository->find($departmentId);
        $user->setDepartment($department);
    
        $image = $request->files->get('image');

        if ($image) {
            $filename = uniqid() . '.' . $image->guessExtension();
            $image->move('uploads/avatars', $filename);
            $user->setAvatar($filename);
        }

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
