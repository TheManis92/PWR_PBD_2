<?php

namespace App\Controller;

use App\Document\EmbedMovieRef;
use App\Document\EmbedUserRef;
use App\Document\Movie;
use App\Document\Review;
use App\Form\ReviewFormType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/reviews", name="_reviews")
 */
class ReviewController extends AbstractController{

    /**
     * @Route("/read/", name="_read", methods={"GET"})
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function read(DocumentManager $documentManager)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }
        $reviews = $documentManager->getRepository(Review::class)->findAllByStatus(false);
        return $this->render('admin/reviews.html.twig', [
            "reviews" => $reviews
        ] );
    }

    /**
     * @Route("/new/{movieId}", name="_new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param DocumentManager $documentManager
     * @param String $movieId
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator, DocumentManager $documentManager, String $movieId)
    {
        $form = $this->createForm(ReviewFormType::class);
        $movie = $documentManager->getRepository(Movie::class)->findOneBy(["id" => $movieId]);
        $previousReviews = $documentManager->getRepository(Review::class)->findAllByUserMovie($this->getUser(), $movie);
        if($previousReviews)
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if (!$form->isValid()) {
                $errors = $validator->validate($form->getData());
                if (count($errors) > 0) {
                    return $this->render('index/index.html.twig',[
                        "error" => $errors,
                        "response_code" => 206]);
                }
            }

            $review = $form->getData();
            $embededUser = new EmbedUserRef();
            $embededUser->setUser($this->getUser());
            $embededUser->setName($this->getUser()->getEmail());
            $embededMovie = new EmbedMovieRef();
            $embededMovie->setMovie($movie);
            $embededMovie->setName($movie->getTitle());
            $review->setAccepted(false);
            if($review->getComment() == '' or $review->getComment() == null ){
                $review->setAccepted(true);
            }

            try {
                $documentManager->persist($review);
                $documentManager->flush();
            }catch (\Exception $e){
                return $this->json(['error' => true]);
            }

            return $this->render('index/index.html.twig',
                [
                    "added" => $form->getData()
                ]
            );
        }
        return $this->render('index/index.html.twig');
    }


    /**
     * @Route("/delete/{id}", name="_delete")
     * @param String $id
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function delete(String $id, DocumentManager $documentManager)
    {
        try {
            $review = $documentManager->getRepository(Review::class)->findOneBy(["id" => $id]);

            $documentManager->remove($review);
            $documentManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => true]);
        } catch (MongoDBException $e) {
        }

        return $this->render('reviews_read');
    }

    /**
     * @Route("/accept/{id}", name="_accept")
     * @param String $id
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function accept(String $id, DocumentManager $documentManager)
    {
        try {
            $review = $documentManager->getRepository(Review::class)->findOneBy(["id" => $id]);

            $review->setAccepted(true);
            $documentManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => true]);
        } catch (MongoDBException $e) {
        }

        return $this->redirectToRoute('_reviews_read');
    }

}
