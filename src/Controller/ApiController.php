<?php

namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Repository\NomenclatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private NomenclatureRepository $nomenclatureRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route(path: '/api2', name: 'taxe_api')]
    public function index(): Response
    {
        $nomenclatures = $this->nomenclatureRepository->findAllGruped();
        $data = $this->serializer->serialize($nomenclatures, 'json', ['groups' => 'group1']);

        return new Response($data);
    }
}
