<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Exercice;
use AcMarche\Taxe\Entity\Taxe;
use AcMarche\Taxe\Form\ExerciceType;
use AcMarche\Taxe\Repository\ExerciceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercice")
 * @IsGranted("ROLE_TAXE")
 */
class ExerciceController extends AbstractController
{

    /**
     * @var ExerciceRepository
     */
    private $exerciceRepository;

    public function __construct(ExerciceRepository $exerciceRepository)
    {
        $this->exerciceRepository = $exerciceRepository;
    }

    /**
     * @Route("/new/{id}", name="exercice_new", methods={"GET","POST"})
     */
    public function new(Request $request, Taxe $taxe): Response
    {
        $exercice = new Exercice();
        $exercice->setTaxe($taxe);
        $exercice->setAnnee(2020);

        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercice);
            $entityManager->flush();

            return $this->redirectToRoute('taxe_show', ['id' => $taxe->getId()]);
        }

        return $this->render(
            '@Taxe/exercice/new.html.twig',
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
            '@Taxe/exercice/edit.html.twig',
            [
                'exercice' => $exercice,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete", name="exercice_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('idexercice');
        $token = $request->get('_tokenauth');

        $exercice = $this->exerciceRepository->find($id);

        if ($exercice === null) {
            $this->createNotFoundException();
        }

        $taxe = $exercice->getTaxe();

        if ($this->isCsrfTokenValid('delete' . $exercice->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxe_show', ['id'=>$taxe->getId()]);
    }
}
