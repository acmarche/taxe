<?php

namespace AcMarche\Taxe\Controller;

use Symfony\Component\HttpFoundation\Response;
use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Form\UserPasswordType;
use AcMarche\Taxe\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/password")
 * @IsGranted("ROLE_TAXE_ADMIN")
 */
class PasswordController extends AbstractController
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/password/{id}", name="taxe_user_password", methods={"GET","POST"})
     *
     */
    public function password(Request $request, User $user): Response
    {
        $em = $this->getDoctrine()->getManager();

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
