<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/country/", name="country")
 */
class CountryController extends AbstractController {

	/**
	 * @Route("read/", name="_read", methods={"GET"})
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	public function read(EntityManagerInterface $entityManager): Response {
		$objects = $entityManager->getRepository(Country::class)
			->findAll();

		return $this->render('country/list.html.twig', [
			"country_list" => $objects
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
		$form = $this->createForm(CountryFormType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if(!$form->isValid())
			{
				$errors = $validator->validate($form->getData());
				if (count($errors) > 0) {
					return $this->render('country/add.html.twig',[
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

			return $this->redirectToRoute('country_new');

		}
		return $this->render('country/add.html.twig', array('form' => $form->createView()));
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
		$form = $this->createForm(CountryFormType::class);

		$form->handleRequest($request);

		$object = $entityManager->getRepository(Country::class)->findOneBy(["id" => $id]);

		if ($form->isSubmitted()) {
			if(!$form->isValid())
			{
				$errors = $validator->validate($form->getData());
				if (count($errors) > 0) {
					return $this->render('country/edit.html.twig',[
						"error" => $errors,
						"response_code" => 206]);
				}
			}

			$object->setName($form->getData()->getName());

			try {
				$entityManager->flush();
			} catch (Exception $e) {
				return $this->render('country/edit.html.twig', [
					'error' => true,
					"form" => $form->createView()
				]);
			}

			return $this->redirectToRoute('country_read');
		}

		return $this->render('country/edit.html.twig', [
			'form' => $form->createView(),
			'country' => $object
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
		$object = $entityManager->getRepository(Country::class)->findOneBy(["id"=>$id]);
		$entityManager->remove($object);
		$entityManager->flush();
		return $this->redirectToRoute('country_read');
	}

}