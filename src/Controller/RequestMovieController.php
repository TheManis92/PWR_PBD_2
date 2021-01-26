<?php

namespace App\Controller;


use App\DBAL\EnumMovieRequestAction;
use App\Entity\Movie;
use App\Entity\MovieRequest;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/request/movie", name="_request_movie")
 */
class RequestMovieController extends AbstractController
{
    /**
     * @Route("/read", name="_read")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function read(EntityManagerInterface $entityManager)
    {

        $requests = $entityManager->getRepository(MovieRequest::class)->findBy([]);
        return $this->render('admin/require_movies.html.twig', [
            "requests" => $requests
        ] );
    }

    /**
     * @Route("/accept/{id}", name="_accept")
     * @param EntityManagerInterface $entityManager
     * @param String $id
     * @return Response
     */
    public function acceptRequest(EntityManagerInterface $entityManager, String $id)
    {
        $request = $entityManager->getRepository(MovieRequest::class)->findOneBy(["id" => $id]);
        if($request->getAction() == EnumMovieRequestAction::ACTION_ADD)
        {
            $newMovie = new Movie();
			$movieSubmission = $request->getMovieSubmission();
            $newMovie->setTitle($movieSubmission->getTitle());
			$newMovie->setYear($movieSubmission->getYear());
			$newMovie->setPlot($movieSubmission->getPlot());
			$newMovie->setGenres($movieSubmission->getGenres());
			$newMovie->setCountries($movieSubmission->getCountries());
			$newMovie->setLangs($movieSubmission->getLangs());
			$newMovie->setCast($movieSubmission->getCast());

			$entityManager->remove($request);
            try{
                $entityManager->persist($newMovie);
                $entityManager->flush();
            } catch(Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
        elseif ($request->getAction() == EnumMovieRequestAction::ACTION_EDIT)
        {
            $movie = $request->getCurrentMovie();
            $movieSubmission = $request->getMovieSubmission();
            $movie->setTitle($movieSubmission->getTitle());
            $movie->setYear($movieSubmission->getYear());
            $movie->setPlot($movieSubmission->getPlot());
            $movie->setGenres($movieSubmission->getGenres());
            $movie->setCountries($movieSubmission->getCountries());
            $movie->setLangs($movieSubmission->getLangs());
            $movie->setCast($movieSubmission->getCast());

			$entityManager->remove($request);
            try{
                $entityManager->flush();
            } catch (Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
        elseif ($request->getAction() == EnumMovieRequestAction::ACTION_REMOVE)
        {
            $movie = $request->getCurrentMovie();

			$entityManager->remove($request);
            try{
                $entityManager->remove($movie);
                $entityManager->flush();
            } catch (Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }

		return $this->redirectToRoute('_request_movie_read');
    }

    /**
     * @Route("/decline/{id}", name="_decline")
     * @param EntityManagerInterface $entityManager
     * @param String $id
     * @return Response
     */
    public function declineRequest(EntityManagerInterface $entityManager, String $id)
    {
        $request = $entityManager->getRepository(MovieRequest::class)->findOneBy(["id" => $id]);
        $entityManager->remove($request);
        try{
            $entityManager->flush();
        } catch(Exception $e) {
            return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
        }

		return $this->redirectToRoute('_request_movie_read');
    }


}