<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Form\UserPasswordType;
use AcMarche\Taxe\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user/password')]
#[IsGranted(data: 'ROLE_TAXE_ADMIN')]
class PasswordController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private UserPasswordHasherInterface $passwordEncoder, private ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '/password/{id}', name: 'taxe_user_password', methods: ['GET', 'POST'])]
    public function password(Request $request, User $user): Response
    {
        $em = $this->managerRegistry->getManager();
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->passwordEncoder->hashPassword($user, $form->getData()->getPlainPassword());
            $user->setPassword($password);
            $em->flush();

            $this->addFlash('success', 'Mot de passe changé');

            return $this->redirectToRoute('taxe_user_show', ['id' => $user->getId()]);
        }

        return $this->render(
            '@Taxe/user/password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
