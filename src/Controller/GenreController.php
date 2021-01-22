<?php

namespace App\Controller;

use App\Document\Genre;
use App\Form\GenreFormType;
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
 * @Route("/genres", name="_genres")
 */
class GenreController extends AbstractController {

    /**
     * @Route("/read", name="_read", methods={"GET"})
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function read(DocumentManager $documentManager)
    {

        $genres = $documentManager->getRepository(Genre::class)->findBy([]);
        return $this->render('test/read_genres.html.twig', [
            "genres" => $genres
        ] );
    }

    /**
     * @Route("/read/{id}", name="_read_id", methods={"GET"})
     * @param String $id
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function readOne(String $id, DocumentManager $documentManager)
    {
        $genre = $documentManager->getRepository(Genre::class)->findOneBy(["id" => $id]);
        return $this->render('test/read_genre.html.twig', [
            "genre" => $genre
        ] );
    }

    /**
     * @Route("/new", name="_new")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator, DocumentManager $documentManager)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }

        if($this->getUser()->getRole()==3){
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
                $documentManager->persist($genre);
                $documentManager->flush();
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
     * @param DocumentManager $documentManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function update(Request $request, String $id,
                           DocumentManager $documentManager, ValidatorInterface $validator
                           )
    {
        $form = $this->createForm(GenreFormType::class);

        $form->handleRequest($request);

        $genre = $documentManager->getRepository(Genre::class)->findOneBy(["id" => $id]);

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
                $documentManager->flush();
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
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function delete(String $id, DocumentManager $documentManager)
    {
        try {
            $genre = $documentManager->getRepository(Genre::class)->findOneBy(["id"=>$id]);
            $documentManager->remove($genre);
            $documentManager->flush();
        } catch (MongoDBException $e) {
        }

        return $this->redirectToRoute('home');
    }

}