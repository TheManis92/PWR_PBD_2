<?php

namespace App\Controller;

use App\Document\User;
use App\Form\UserFormType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="registration")
     * @param Request $request
     * @param DocumentManager $dm
     * @return RedirectResponse|Response
     * @throws MongoDBException
     */
    public function registerAction(Request $request,DocumentManager $dm)
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UserFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setPassword($form->get('password')->getData());
            $user->setName($form->get('name')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setJoined(new \DateTime());
            $dm->persist($user);
            $dm->flush();

            return $this->redirect('/');
        }


        return $this->render('registration/registration.html.twig', array('form' => $form->createView(), 'page_tabs' => 'registration'));
    }


}