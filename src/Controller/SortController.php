<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
#[Route(path: '/sort')]
#[IsGranted('ROLE_TAXE_ADMIN')]
class SortController extends AbstractController
{
    public function __construct(private TaxeRepository $taxeRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/', name: 'taxe_tri')]
    public function index(): Response
    {
        $taxes = $this->taxeRepository->findAllSorted();

        return $this->render(
            '@Taxe/position/index.html.twig',
            [
                'taxes' => $taxes,
            ]
        );
    }

    /**
     * Trier les news.
     */
    #[Route(path: '/request', name: 'taxe_request_trier', methods: ['GET', 'POST'])]
    public function trier(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), null, 512, JSON_THROW_ON_ERROR);
        $taxes = $data->taxes;
        foreach ($taxes as $position => $taxeId) {
            $taxe = $this->taxeRepository->find($taxeId);
            if (null !== $taxe) {
                $taxe->setPosition($position);
            }
        }
        $this->entityManager->flush();

        return $this->json('<div class="alert alert-success">Tri enregistré</div>');
    }
}
