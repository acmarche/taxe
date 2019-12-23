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
    /**
     * @var TaxeRepository
     */
    private $taxeRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TaxeRepository $taxeRepository, EntityManagerInterface $entityManager)
    {
        $this->taxeRepository = $taxeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="taxe_tri")
     */
    public function index()
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
    public function trier(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $news = $request->request->get('news');
            if (is_array($news)) {
                foreach ($news as $position => $newsId) {
                    $taxe = $this->taxeRepository->find($newsId);
                    if ($taxe) {
                        $taxe->setPosition($position);
                    }
                }
                $this->entityManager->flush();

                return new Response('<div class="alert alert-success">Tri enregistré</div>');
            }

            return new Response('<div class="alert alert-error">Erreur</div>');
        }

         return new Response('<div class="alert alert-error">Erreur</div>');
    }

}