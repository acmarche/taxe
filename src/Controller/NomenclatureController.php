<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Nomenclature;
use AcMarche\Taxe\Form\NomenclatureType;
use AcMarche\Taxe\Repository\NomenclatureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/nomenclature')]
#[IsGranted(data: 'ROLE_TAXE_ADMIN')]
class NomenclatureController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '/', name: 'nomenclature_index', methods: ['GET'])]
    public function index(NomenclatureRepository $nomenclatureRepository): Response
    {
        return $this->render('@Taxe/nomenclature/index.html.twig', [
            'nomenclatures' => $nomenclatureRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'nomenclature_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $nomenclature = new Nomenclature();
        $form = $this->createForm(NomenclatureType::class, $nomenclature);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($nomenclature);
            $entityManager->flush();

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('@Taxe/nomenclature/new.html.twig', [
            'nomenclature' => $nomenclature,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'nomenclature_show', methods: ['GET'])]
    public function show(Nomenclature $nomenclature): Response
    {
        return $this->render('@Taxe/nomenclature/show.html.twig', [
            'nomenclature' => $nomenclature,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'nomenclature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nomenclature $nomenclature): Response
    {
        $form = $this->createForm(NomenclatureType::class, $nomenclature);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('@Taxe/nomenclature/edit.html.twig', [
            'nomenclature' => $nomenclature,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'nomenclature_delete', methods: ['POST'])]
    public function delete(Request $request, Nomenclature $nomenclature): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$nomenclature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($nomenclature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('nomenclature_index');
    }
}
