<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/person/", name="person")
 */
class PersonController extends AbstractController {

	/**
	 * @Route("read/", name="_read", methods={"GET"})
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function read(EntityManagerInterface $entityManager): Response {
		$objects = $entityManager->getRepository(Person::class)
			->findAll();

		return $this->render('person/list.html.twig', [
			"person_list" => $objects
		]);
	}

	/**
	 * @Route("new/", name="_new")
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

		if($this->getUser()->getRole()->getRole()=="ROLE_USER") {
			return $this->redirectToRoute('home');
		}
		$form = $this->createForm(PersonFormType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if(!$form->isValid())
			{
				$errors = $validator->validate($form->getData());
				if (count($errors) > 0) {
					return $this->render('person/add.html.twig',[
						"error" => $errors,
						"response_code" => 206]);
				}
			}

			$object = $form->getData();


			try {
				$entityManager->persist($object);
				$entityManager->flush();
			}catch (Exception $e){
				return $this->json(['error' => true]);
			}

			return $this->redirectToRoute('person_new');

		}
		return $this->render('person/add.html.twig', array('form' => $form->createView()));
	}

	/**
	 * @Route("update/{id}/", name="_update")
	 * @param Request $request
	 * @param String $id
	 * @param EntityManagerInterface $entityManager
	 * @param ValidatorInterface $validator
	 * @return Response
	 */
	public function update(Request $request, String $id,
						   EntityManagerInterface $entityManager, ValidatorInterface $validator)
	{
		$form = $this->createForm(PersonFormType::class);

		$form->handleRequest($request);

		$object = $entityManager->getRepository(Person::class)->findOneBy(["id" => $id]);

		if ($form->isSubmitted()) {
			if(!$form->isValid())
			{
				$errors = $validator->validate($form->getData());
				if (count($errors) > 0) {
					return $this->render('person/edit.html.twig',[
						"error" => $errors,
						"response_code" => 206]);
				}
			}

			$object->setName($form->getData()->getName());

			try {
				$entityManager->flush();
			} catch (Exception $e) {
				return $this->render('person/edit.html.twig', [
					'error' => true,
					"form" => $form->createView()
				]);
			}

			return $this->redirectToRoute('person_read');
		}

		return $this->render('person/edit.html.twig', [
			'form' => $form->createView(),
			'person' => $object
		]);
	}

	/**
	 * @Route("delete/{id}/", name="_delete")
	 * @param String $id
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function delete(String $id, EntityManagerInterface $entityManager)
	{
		$object = $entityManager->getRepository(Person::class)->findOneBy(["id"=>$id]);
		$entityManager->remove($object);
		$entityManager->flush();
		return $this->redirectToRoute('person_read');
	}

}