<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Exercice;
use AcMarche\Taxe\Entity\Taxe;
use AcMarche\Taxe\Form\ExerciceType;
use AcMarche\Taxe\Repository\ExerciceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/exercice')]
#[IsGranted('ROLE_TAXE_ADMIN')]
class ExerciceController extends AbstractController
{
    public function __construct(private readonly ExerciceRepository $exerciceRepository, private readonly ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '/new/{id}', name: 'exercice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Taxe $taxe): Response
    {
        $exercice = new Exercice();
        $exercice->setTaxe($taxe);
        $exercice->setAnnee('2020-2025');

        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
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

    #[Route(path: '/{id}/edit', name: 'exercice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exercice $exercice): Response
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();

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

    #[Route(path: '/delete', name: 'exercice_delete', methods: ['POST'])]
    public function delete(Request $request): RedirectResponse
    {
        $id = $request->get('idexercice');
        $token = $request->get('_tokenauth');
        $exercice = $this->exerciceRepository->find($id);
        if (!$exercice instanceof Exercice) {
            $this->createNotFoundException();
        }

        $taxe = $exercice->getTaxe();
        if ($this->isCsrfTokenValid('delete'.$exercice->getId(), $token)) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($exercice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxe_show', ['id' => $taxe->getId()]);
    }
}
