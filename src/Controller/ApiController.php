<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Repository\NomenclatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly NomenclatureRepository $nomenclatureRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route(path: '/api2', name: 'taxe_api')]
    public function index(): JsonResponse
    {
        $nomenclatures = $this->nomenclatureRepository->findAllGrouped();
        $data = [];
        foreach ($nomenclatures as $nomenclature) {
            $row = ['id' => $nomenclature->getId(), 'nom' => $nomenclature->getNom(), 'taxes' => []];
            foreach ($nomenclature->getTaxes() as $taxe) {
                $exercices = [];
                foreach ($taxe->getExercices() as $exercice) {
                    $exercices[] = [
                        'id' => $exercice->getId(),
                        'annee' => $exercice->getAnnee(),
                        'url' => 'https://extranet.marche.be/files/taxes/'.$exercice->getFileName(),
                        'fileName' => $exercice->getFileName(),
                    ];
                }
                $row['taxes'][] = [
                    'id' => $taxe->getId(),
                    'nom' => $taxe->getNom(),
                    'position' => $taxe->getPosition(),
                    'exercices' => $exercices,
                ];
                $data[] = $row;
            }
        }

        return new JsonResponse($data);
    }
}
