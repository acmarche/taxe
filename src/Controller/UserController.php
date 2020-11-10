<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Form\UserEditType;
use AcMarche\Taxe\Form\UserType;
use AcMarche\Taxe\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_TAXE_ADMIN")
 */
class UserController extends AbstractController
{
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Lists all Utilisateur entities.
     *
     * @Route("/", name="taxe_user", methods={"GET"})
     *
     */
    public function index()
    {
        $users = $this->userRepository->findBy([], ['nom' => 'ASC']);

        return $this->render(
            '@Taxe/user/index.html.twig',
            array(
                'users' => $users,
            )
        );
    }

    /**
     *
     *
     * @Route("/new", name="taxe_user_new", methods={"GET","POST"})
     *
     */
    public function new(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->getData()->getPlainPassword())
            );
            $this->userRepository->insert($user);

            $this->addFlash("success", "L'utilisateur a bien été ajouté");

            return $this->redirectToRoute('taxe_user');
        }

        return $this->render(
            '@Taxe/user/new.html.twig',
            array(
                'user' => $user,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/{id}", name="taxe_user_show", methods={"GET"})
     *
     */
    public function show(User $user)
    {
        return $this->render(
            '@Taxe/user/show.html.twig',
            array(
                'user' => $user,
            )
        );
    }

    /**
     * @Route("/{id}/edit", name="taxe_user_edit", methods={"GET","POST"})
     *
     */
    public function edit(Request $request, User $user)
    {
        $editForm = $this->createForm(UserEditType::class, $user);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->userRepository->flush();
            $this->addFlash("success", "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('taxe_user');
        }

        return $this->render(
            '@Taxe/user/edit.html.twig',
            array(
                'user' => $user,
                'form' => $editForm->createView(),
            )
        );
    }

    /**
     *
     * @Route("/{id}", name="taxe_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été supprimé');
        }

        return $this->redirectToRoute('taxe_user');
    }

}
