<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/genres", name="_genres")
 */
class GenreController extends AbstractController {

    /**
     * @Route("/read", name="_read", methods={"GET"})
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function read(EntityManagerInterface $entityManager)
    {

        $genres = $entityManager->getRepository(Genre::class)->findBy([]);
        return $this->render('test/read_genres.html.twig', [
            "genres" => $genres
        ] );
    }

    /**
     * @Route("/read/{id}", name="_read_id", methods={"GET"})
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function readOne(String $id, EntityManagerInterface $entityManager)
    {
        $genre = $entityManager->getRepository(Genre::class)->findOneBy(["id" => $id]);
        return $this->render('test/read_genre.html.twig', [
            "genre" => $genre
        ] );
    }

    /**
     * @Route("/new", name="_new")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }

        if($this->getUser()->getRole()->getName()=="ROLE_USER"){
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(GenreFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if(!$form->isValid())
            {
                $errors = $validator->validate($form->getData());
                if (count($errors) > 0) {
                    return $this->render('index/index.html.twig',[
                        "error" => $errors,
                        "response_code" => 206]);
                }
            }

            $genre = $form->getData();


            try {
                $entityManager->persist($genre);
                $entityManager->flush();
            }catch (\Exception $e){
                return $this->json(['error' => true]);
            }

            return $this->redirectToRoute('_user_account');

        }
        return $this->render('admin/genre.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/update/{id}", name="_update")
     * @param Request $request
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function update(Request $request, String $id,
                           EntityManagerInterface $entityManager, ValidatorInterface $validator
                           )
    {
        $form = $this->createForm(GenreFormType::class);

        $form->handleRequest($request);

        $genre = $entityManager->getRepository(Genre::class)->findOneBy(["id" => $id]);

        if ($form->isSubmitted()) {
            if(!$form->isValid())
            {
                $errors = $validator->validate($form->getData());
                if (count($errors) > 0) {
                    return $this->render('index/index.html.twig',[
                        "error" => $errors,
                        "response_code" => 206]);
                }
            }

            $genre->setName($form->getData()->getName());

            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->render('index/index.html.twig', ['error' => true]);
            }

            return $this->render('index/index.html.twig', [
               "edited" => $form->getData()
            ]);
        }

        return $this->render('test/update_genre.html.twig',['form' => $form->createView(), 'genre' => $genre]);
    }

    /**
     * @Route("/delete/{id}", name="_delete")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(String $id, EntityManagerInterface $entityManager)
    {
        $genre = $entityManager->getRepository(Genre::class)->findOneBy(["id"=>$id]);
        $entityManager->remove($genre);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

}