<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Exercice;
use AcMarche\Taxe\Entity\Taxe;
use AcMarche\Taxe\Form\ExerciceType;
use AcMarche\Taxe\Repository\ExerciceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercice")
 */
class ExerciceController extends AbstractController
{

    /**
     * @Route("/new/{id}", name="exercice_new", methods={"GET","POST"})
     */
    public function new(Request $request, Taxe $taxe): Response
    {
        $exercice = new Exercice();
        $exercice->setTaxe($taxe);
        $exercice->setAnnee(2019);

        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirectToRoute('taxe_show', ['id' => $taxe->getId()]);
        }

        return $this->render(
            'exercice/new.html.twig',
            [
                'exercice' => $exercice,
                'taxe' => $taxe,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="exercice_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exercice $exercice): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exercice_index');
        }

        return $this->render(
            'exercice/edit.html.twig',
            [
                'exercice' => $exercice,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="exercice_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exercice $exercice): Response
    {
        if ($this->isCsrfTokenValid('delete' . $exercice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('exercice_index');
    }
}
