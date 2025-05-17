<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private DepartmentRepository $departmentRepo
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Создание нового пользователя')
            ->addArgument('first_name', InputArgument::REQUIRED)
            ->addArgument('last_name', InputArgument::REQUIRED)
            ->addArgument('age', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('status', InputArgument::REQUIRED)
            ->addArgument('telegram', InputArgument::REQUIRED)
            ->addArgument('address', InputArgument::REQUIRED)
            ->addArgument('department_id', InputArgument::REQUIRED);
    }
    

    protected function execute(InputInterface $input, OutputInterface $output): int
{
    $department = $this->departmentRepo->find($input->getArgument('department_id'));

    if (!$department) {
        $output->writeln('Отдел не найден');
        return Command::FAILURE;
    }

    $user = new User();
    $user->setFirstName($input->getArgument('first_name'));
    $user->setLastName($input->getArgument('last_name'));
    $user->setAge($input->getArgument('age'));
    $user->setEmail($input->getArgument('email'));
    $user->setStatus($input->getArgument('status'));
    $user->setTelegram($input->getArgument('telegram'));
    $user->setAddress($input->getArgument('address'));
    $user->setDepartment($department);

    $this->em->persist($user);
    $this->em->flush();

    $output->writeln('Пользователь создан');
    return Command::SUCCESS;
}

}
