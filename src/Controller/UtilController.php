<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilController extends AbstractController
{
    #[Route('/util/empty-modal-confirmation', name: 'app_util_empty_modal_confirmation')]
    public function emptyModalConfirmation(): Response
    {
        return $this->render('util/empty_modal_confirmation.html.twig');
    }
}
