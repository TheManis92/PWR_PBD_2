<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Entity\MovieRequest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/read/{id}", name="_read_one", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param String $id
     * @return Response
     */
    public function readOne(EntityManagerInterface $entityManager, String $id)
    {

        $request = $entityManager->getRepository(MovieRequest::class)->findOneBy(["id" => $id]);
        return $this->render('test/read_request_movie.html.twig', [
            "request" => $request
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
        $request->setApproved(true);
        $request->setCloses(new \DateTime());
        if($request->getAction() == 1)
        {
            $newMovie = new Movie();
            $newMovie->setTitle($request->getNewMovie()->getTitle());
            $newMovie->setYear($request->getNewMovie()->getYear());
            $newMovie->setRating($request->getNewMovie()->getRating());
            $newMovie->setPlot($request->getNewMovie()->getPlot());
            $newMovie->setGenres($request->getNewMovie()->getGenres());
            try{
                $entityManager->persist($newMovie);
                $entityManager->flush();
            } catch(\Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
        elseif ($request->getAction() == 2)
        {
            $movie = $request->getMovie()->getMovie();
            $movie->setTitle($request->getNewMovie()->getTitle());
            $movie->setYear($request->getNewMovie()->getYear());
            $movie->setRating($request->getNewMovie()->getRating());
            $movie->setPlot($request->getNewMovie()->getPlot());
            $movie->setGenres($request->getNewMovie()->getGenres());
            try{
                $entityManager->flush();
            } catch (\Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
        elseif ($request->getAction() == 3)
        {
            $movie = $request->getMovie()->getMovie();
            try{
                $entityManager->remove($movie);
                $entityManager->flush();
            } catch (\Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
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
        $request->setApproved(false);
        $request->setCloses(new \DateTime());
        try{
            $entityManager->flush();
        } catch(\Exception $e) {
            return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
        }
    }


}