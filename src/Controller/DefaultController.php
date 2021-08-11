<?php


namespace AcMarche\Taxe\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package AcMarche\Taxe\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="taxe_home")
     */
    public function index(): Response
    {
        return new Response('coucou');
    }
}