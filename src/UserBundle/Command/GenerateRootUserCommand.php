<?php

namespace UserBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use UserBundle\Service\UserManager;

#[AsCommand(name: "user:root:generate")]
class GenerateRootUserCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ParameterBagInterface $params,
        protected UserManager $userManager,
    )
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $this->params->get('root_user_username');
        $password = $this->params->get('root_user_password');

        $rootUser = $this->userManager->newInstance(
            email: 'root@root.root',
            password: $password,
            username: $username,
        );

        $rootUser
            ->setIsVerified(true)
            ->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER']);

        $this->em->persist($rootUser);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
