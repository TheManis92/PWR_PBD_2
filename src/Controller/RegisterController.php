<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
	/**
	 * @Route("/register", name="registration")
	 * @param Request $request
	 * @param EntityManagerInterface $em
	 * @return RedirectResponse|Response
	 */
    public function registerAction(Request $request, EntityManagerInterface $em)
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UserFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$roleUser = $em->getRepository(Role::class)
				->findOneBy(['role' => ROLE::ROLE_USER]);

            $user = new User();
            $user->setPassword($form->get('password')->getData());
            $user->setName($form->get('name')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setRole($roleUser);
            $em->persist($user);
            $em->flush();

            return $this->redirect('/');
        }


        return $this->render('registration/registration.html.twig', array('form' => $form->createView(), 'page_tabs' => 'registration'));
    }


}