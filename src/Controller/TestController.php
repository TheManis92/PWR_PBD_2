<?php


namespace App\Controller;


use App\Repository\ReviewRepository;
use Doctrine\DBAL\ConnectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends AbstractController {

	/**
	 * @Route("/test/", name="_test", methods={"GET"})
	 * @param ReviewRepository $reviewRepo
	 * @return RedirectResponse|Response
	 * @throws ConnectionException
	 */
	public function test(ReviewRepository $reviewRepo): Response {
		$result = $reviewRepo->updateMoviesAverageRating();
		var_dump($result);
		return $this->render('index/index.html.twig');
	}
}