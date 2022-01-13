<?php

namespace AcMarche\Taxe\Command;

use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateuserCommand extends Command
{
    protected static $defaultName = 'taxe:create-user';

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordEncoder,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Création d\'un utilisateur')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $role = 'ROLE_TAXE_ADMIN';
        $rolePatrimoine = 'ROLE_PATRIMOINE_ADMIN';

        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Adresse email non valide');

            return 1;
        }

        if (\strlen($name) < 1) {
            $io->error('Name minium 1');

            return 1;
        }

        if (!$password) {
            $question = new Question("Choisissez un mot de passe: \n");
            $question->setHidden(true);
            $question->setMaxAttempts(5);
            $question->setValidator(
                function ($password) {
                    if (\strlen($password) < 4) {
                        throw new RuntimeException('Le mot de passe doit faire minimum 4 caractères');
                    }

                    return $password;
                }
            );
            $password = $helper->ask($input, $output, $question);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        $questionAdministrator = new ConfirmationQuestion("Administrateur ? [Y,n] \n", true);
        $administrator = $helper->ask($input, $output, $questionAdministrator);

        if (null === $user) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($email);
            $user->setNom($name);
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
            $this->entityManager->persist($user);
        }

        if ($administrator) {
            $user->addRole($role);
            $user->addRole($rolePatrimoine);
        }

        $this->entityManager->flush();

        $io->success("L'utilisateur a bien été créé");

        return 0;
    }
}
