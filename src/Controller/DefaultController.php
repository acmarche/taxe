<?php

namespace AcMarche\Taxe\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'taxe_home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('taxe_index');
    }
}
