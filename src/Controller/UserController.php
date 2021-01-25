<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\AdminUserFormType;
use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Comment\Doc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user", name="_user")
 */

class UserController extends AbstractController{
    /**
     * @Route("/account", name="_account")
     */
    public function myAccount()
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }
        return $this->render('user/user.html.twig', ["page_tabs" => '_user_account']);
    }


    /**
     * @Route("/edit/{id}", name="_edit")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return Response
     */

    public function edit(String $id, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request)
    {
        if(!$this->getUser())
            return $this->redirectToRoute('login');
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $id]);
        if($this->getUser()->getId() == $user->getId())
        {
            $form = $this->createForm(UserFormType::class);
            $form->remove('password');
            $form->handleRequest($request);

            if($form->isSubmitted())
            {
                if (!$form->isValid()) {
                    $errors = $validator->validate($form->getData());
                    if (count($errors) > 0) {
                        return $this->render('index/index.html.twig',[
                            "error" => $errors,
                            "response_code" => 206]);
                    }
                }

                $updatedUser = $form->getData();
                $user->setName($updatedUser->getName());
                $user->setEmail($updatedUser->getEmail());
                try{
                    $entityManager->flush();
                } catch (\Exception $e) {
                    return $this->render('test/error.html.twig', ["error" => $e]);
                }

                return $this->redirectToRoute('_user_account');
            }

            return $this->render('user/settings.html.twig', ["form" => $form->createView(), ["page_tabs" => '_user_account']]);

        }

        else if($this->getUser()->getRole() == 1)
        {
            $form = $this->createForm(AdminUserFormType::class);
            $form->handleRequest($request);

            if($form->isSubmitted())
            {
                if (!$form->isValid()) {
                    $errors = $validator->validate($form->getData());
                    if (count($errors) > 0) {
                        return $this->render('index/index.html.twig',[
                            "error" => $errors,
                            "response_code" => 206]);
                    }
                }

                $updatedUser = $form->getData();
                $user->setName($updatedUser->getName());
                $user->setEmail($updatedUser->getEmail());
                $user->setRole($updatedUser->getRole());
                $user->setPassword($updatedUser->getPassword());
                try{
                    $entityManager->flush();
                } catch (\Exception $e) {
                    return $this->render('test/error.html.twig', ["error" => $e]);
                }

                return $this->redirectToRoute('home');
            }

            return $this->render('test/edit_user.html.twig', ["form" => $form->createView()]);

        }


    }

    /**
     * @Route("/update/password/{id}", name="_update_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, String $id, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $id]);
        $form = $this->createForm(ChangePasswordFormType::class);
        if($this->getUser()->getId() == $user->getId()){

            if($form->isSubmitted())
            {
                if (!$form->isValid()) {
                    $errors = $validator->validate($form->getData());
                    if (count($errors) > 0) {
                        return $this->render('index/index.html.twig',[
                            "error" => $errors,
                            "response_code" => 206]);
                    }
                }

                if(password_verify($form->get('oldPassword'), $this->getUser()->getPassword()))
                {
                    $user->setPassword($form->get('newPassword'));

                    try{
                        $entityManager->flush();
                    } catch (\Exception $e)
                    {
                        return $this->render('test/error.html.twig', ["error" => $e]);
                    }

                    return $this->redirectToRoute('_user_account');

                }
            }

            return $this->render('user/update_password.html.twig', ["form" => $form->createView(),"page_tabs" => '_user_account']);

        }

    }


    /**
     * @Route("/watchlist/", name="_watchlist")
     */
    public function showWatchlist()
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/watchlist.html.twig', ["watchlist" => $this->getUser()->getWatchlist(),"page_tabs" => '_user_account']);

    }

    /**
     * @Route("/reviews/", name="_reviews")
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function showUserReviews(EntityManagerInterface $entityManager)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }

        $reviews = $entityManager->getRepository(Review::class)->findBy(["user" => $this->getUser()]);

        return $this->render('user/reviews.html.twig', ["reviews" => $reviews, "page_tabs" => '_user_account']);

    }

    /**
     * @Route("/last/visit/", name="_last_visit")
     * @return RedirectResponse|Response
     */
    public function setLastVisit()
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('login');
        }

       $this->getUser()->setLastVisit(new \DateTime());

        return $this->redirectToRoute('home');

    }


}