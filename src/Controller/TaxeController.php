<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Entity\Taxe;
use AcMarche\Taxe\Form\SearchTaxeType;
use AcMarche\Taxe\Form\TaxeType;
use AcMarche\Taxe\Repository\TaxeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/taxe')]
#[IsGranted('ROLE_TAXE_ADMIN')]
class TaxeController extends AbstractController
{
    public function __construct(private TaxeRepository $taxeRepository, private ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '/', name: 'taxe_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchTaxeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $taxes = $this->taxeRepository->search($data->getNom(), $data->getNomenclature());
        } else {
            $taxes = $this->taxeRepository->findAll();
        }

        return $this->render(
            '@Taxe/taxe/index.html.twig',
            [
                'form' => $form->createView(),
                'taxes' => $taxes,
            ]
        );
    }

    #[Route(path: '/new', name: 'taxe_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $taxe = new Taxe();
        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($taxe);
            $entityManager->flush();

            return $this->redirectToRoute('taxe_index');
        }

        return $this->render(
            '@Taxe/taxe/new.html.twig',
            [
                'taxe' => $taxe,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'taxe_show', methods: ['GET'])]
    public function show(Taxe $taxe): Response
    {
        return $this->render(
            '@Taxe/taxe/show.html.twig',
            [
                'taxe' => $taxe,
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: 'taxe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Taxe $taxe): Response
    {
        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->managerRegistry->getManager()->flush();

            return $this->redirectToRoute('taxe_index');
        }

        return $this->render(
            '@Taxe/taxe/edit.html.twig',
            [
                'taxe' => $taxe,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/delete', name: 'taxe_delete', methods: ['POST'])]
    public function delete(Request $request, Taxe $taxe): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$taxe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($taxe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxe_index');
    }
}
