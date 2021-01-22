<?php

namespace App\Controller;

use App\Document\Movie;
use App\Document\RequestMovie;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
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
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function read(DocumentManager $documentManager)
    {

        $requests = $documentManager->getRepository(RequestMovie::class)->findBy([]);
        return $this->render('admin/require_movies.html.twig', [
            "requests" => $requests
        ] );
    }

    /**
     * @Route("/read/{id}", name="_read_one", methods={"GET"})
     * @param DocumentManager $documentManager
     * @param String $id
     * @return Response
     */
    public function readOne(DocumentManager $documentManager, String $id)
    {

        $request = $documentManager->getRepository(RequestMovie::class)->findOneBy(["id" => $id]);
        return $this->render('test/read_request_movie.html.twig', [
            "request" => $request
        ] );
    }


    /**
     * @Route("/accept/{id}", name="_accept")
     * @param DocumentManager $documentManager
     * @param String $id
     * @return Response
     */
    public function acceptRequest(DocumentManager $documentManager, String $id)
    {
        $request = $documentManager->getRepository(RequestMovie::class)->findOneBy(["id" => $id]);
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
                $documentManager->persist($newMovie);
                $documentManager->flush();
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
                $documentManager->flush();
            } catch (\Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
        elseif ($request->getAction() == 3)
        {
            $movie = $request->getMovie()->getMovie();
            try{
                $documentManager->remove($movie);
                $documentManager->flush();
            } catch (\Exception $e)
            {
                return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * @Route("/decline/{id}", name="_decline")
     * @param DocumentManager $documentManager
     * @param String $id
     * @return Response
     */
    public function declineRequest(DocumentManager $documentManager, String $id)
    {
        $request = $documentManager->getRepository(RequestMovie::class)->findOneBy(["id" => $id]);
        $request->setApproved(false);
        $request->setCloses(new \DateTime());
        try{
            $documentManager->flush();
        } catch(\Exception $e) {
            return $this->render('test/error.html.twig', ['error' => $e->getMessage()]);
        }
    }


}