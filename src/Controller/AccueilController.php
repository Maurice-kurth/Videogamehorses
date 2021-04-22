<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    public function accueil()
    {

        return $this->render('accueil.html.twig');

    }
}