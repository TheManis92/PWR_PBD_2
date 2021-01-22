<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Home extends AbstractController {

	/**
	 * @Route("/", name="home")
	 */
    public function index() {
        return $this->render('index/index.html.twig', ["page_tabs" => 'home']);
    }
}
