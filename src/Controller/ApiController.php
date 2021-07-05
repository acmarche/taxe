<?php


namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Repository\NomenclatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class DefaultController
 * @package AcMarche\Taxe\Controller
 */
class ApiController extends AbstractController
{
    private NomenclatureRepository $nomenclatureRepository;
    private SerializerInterface $serializer;

    public function __construct(NomenclatureRepository $nomenclatureRepository, SerializerInterface $serializer)
    {
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api2", name="taxe_api")
     */
    public function index(): Response
    {
        $nomenclatures = $this->nomenclatureRepository->findAllGruped();

        $data = $this->serializer->serialize($nomenclatures, 'json', ['groups' => 'group1']);

        return new Response($data);
    }
}
