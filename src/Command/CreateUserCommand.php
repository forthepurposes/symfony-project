<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as UserPasswordEncoderInterface;
use Symfony\Component\String\ByteString;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add new user to database ',
    hidden: false,
    aliases: ['a:c-u']
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordEncoderInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Username: '. $input->getArgument('email'));

        $user = $this->userRepository->findOneBy(['email' => $input->getArgument('email')]);
        if($user){
            $output->writeln('User already exists');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($input->getArgument('email'));
        $user->setRoles([]);
        $plaintextPassword = ByteString::fromRandom(12)->toString();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $output->writeln('Password: '. $hashedPassword);
        $user->setPassword($hashedPassword);

        $output->writeln([
            'User Creator',
            '============',
            'Password: '. $plaintextPassword,
        ]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
