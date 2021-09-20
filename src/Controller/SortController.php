<?php


namespace AcMarche\Taxe\Controller;

use AcMarche\Taxe\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package AcMarche\Taxe\Controller
 * @Route("/sort")
 * @IsGranted("ROLE_TAXE_ADMIN")
 */
class SortController extends AbstractController
{
    private TaxeRepository $taxeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TaxeRepository $taxeRepository, EntityManagerInterface $entityManager)
    {
        $this->taxeRepository = $taxeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="taxe_tri")
     */
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
     *
     * @Route("/request", name="taxe_request_trier", methods={"GET", "POST"})
     */
    public function trier(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $taxes = $data->taxes;
        foreach ($taxes as $position => $taxeId) {
            $taxe = $this->taxeRepository->find($taxeId);
            if ($taxe !== null) {
                $taxe->setPosition($position);
            }
        }
        $this->entityManager->flush();

        return $this->json('<div class="alert alert-success">Tri enregistré</div>');
    }

}
