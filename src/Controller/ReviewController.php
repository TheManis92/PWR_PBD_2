<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewFormType;

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
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function read(EntityManagerInterface $entityManager)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }
        $reviews = $entityManager->getRepository(Review::class)->findBy(["isAccepted"=>false]);
        return $this->render('admin/reviews.html.twig', [
            "reviews" => $reviews
        ] );
    }

    /**
     * @Route("/new/{movieId}", name="_new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param String $movieId
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, String $movieId)
    {
        $form = $this->createForm(ReviewFormType::class);
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id" => $movieId]);
        $previousReviews = $entityManager->getRepository(Review::class)->findAllByUserMovie($this->getUser(), $movie);
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
                $entityManager->persist($review);
                $entityManager->flush();
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
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(String $id, EntityManagerInterface $entityManager)
    {
        try {
            $review = $entityManager->getRepository(Review::class)->findOneBy(["id" => $id]);

            $entityManager->remove($review);
            $entityManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => true]);
        }

        $reviews = $entityManager->getRepository(Review::class)->findBy(["isAccepted"=>false]);
        return $this->render('admin/reviews.html.twig', [
            "reviews" => $reviews
        ] );
    }

    /**
     * @Route("/accept/{id}", name="_accept")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function accept(String $id, EntityManagerInterface $entityManager)
    {
        try {
            $review = $entityManager->getRepository(Review::class)->findOneBy(["id" => $id]);

            $review->setAccepted(true);
            $entityManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => true]);
        }

        return $this->redirectToRoute('_reviews_read');
    }

}
