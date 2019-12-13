<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Taxe;
use AcMarche\Taxe\Form\TaxeType;
use AcMarche\Taxe\Repository\TaxeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/taxe")
 */
class TaxeController extends AbstractController
{
    /**
     * @Route("/", name="taxe_index", methods={"GET"})
     */
    public function index(TaxeRepository $taxeRepository): Response
    {
        return $this->render('taxe/index.html.twig', [
            'taxes' => $taxeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="taxe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $taxe = new Taxe();
        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taxe);
            $entityManager->flush();

            return $this->redirectToRoute('taxe_index');
        }

        return $this->render('taxe/new.html.twig', [
            'taxe' => $taxe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taxe_show", methods={"GET"})
     */
    public function show(Taxe $taxe): Response
    {
        return $this->render('taxe/show.html.twig', [
            'taxe' => $taxe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="taxe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Taxe $taxe): Response
    {
        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('taxe_index');
        }

        return $this->render('taxe/edit.html.twig', [
            'taxe' => $taxe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taxe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Taxe $taxe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taxe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxe_index');
    }
}
