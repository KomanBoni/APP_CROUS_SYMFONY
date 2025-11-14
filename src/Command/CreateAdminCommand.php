<?php

namespace App\Command;

use App\Entity\Koman;
use App\Repository\KomanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un utilisateur administrateur',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private KomanRepository $komanRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'administrateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'administrateur')
            ->addOption('promote', 'p', InputOption::VALUE_NONE, 'Promouvoir un utilisateur existant en admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $promote = $input->getOption('promote');

        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->komanRepository->findOneBy(['email' => $email]);

        if ($existingUser) {
            if ($promote) {
                // Promouvoir l'utilisateur existant en admin
                $existingUser->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
                $this->entityManager->flush();
                
                $io->success(sprintf('L\'utilisateur %s a été promu administrateur avec succès !', $email));
                return Command::SUCCESS;
            } else {
                $io->error(sprintf('Un utilisateur avec l\'email %s existe déjà. Utilisez --promote pour le promouvoir en admin.', $email));
                return Command::FAILURE;
            }
        }

        // Créer un nouvel utilisateur admin
        $user = new Koman();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Administrateur créé avec succès ! Email: %s', $email));

        return Command::SUCCESS;
    }
}

