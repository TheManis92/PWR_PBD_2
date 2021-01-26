<?php

namespace App\Controller;

use App\DBAL\EnumMovieRequestAction;
use App\Entity\Country;
use App\Entity\Genre;
use App\Entity\Lang;
use App\Entity\Movie;
use App\Entity\MovieRequest;
use App\Entity\Person;
use App\Entity\Review;
use App\Form\MovieFormType;
use App\Form\ReviewFormType;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/movies", name="movies")
 */
class MovieController extends AbstractController{
    /**
     * @Route("/read/{from}/{to}/{page}", name="_read", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param Int $from
     * @param Int $to
     * @param Int $page
     * @return Response
     */
    public function read(EntityManagerInterface $entityManager, Int $from, Int $to, Int $page=1)
    {

        $movies = $entityManager->getRepository(Movie::class)->findAllEx($from, $to);
        $count = $entityManager->getRepository(Movie::class)->getCount();
        var_dump($movies);
        return $this->render('movies/movies.html.twig', [
            "movies" => $movies,
            "page_tabs" => 'movies_read',
            "count" => $count,
            "from" => $from,
            "to" => $to,
            "page" => $page
        ] );
    }

    /**
     * @Route("/read/{id}", name="_read_one")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function readOne(String $id, EntityManagerInterface $entityManager)
    {
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id"=>$id]);
        $cast = $movie->getCast();
        if ($cast !== null) {
        	$it = $cast->getIterator();
        	$it->uasort(
        		fn($first, $second) =>
				$first->getPriority() > $second->getPriority() ? 1 : -1
			);
		}
        return $this->render('movies/details.html.twig', [
            "movie" => $movie,
            "cast"	=> $it ?? [],
            "page_tabs" => 'movies_read'
        ] );
    }

    /**
     * @Route("/reviews/{id}", name="_reviews")
     * @param Request $request
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function readReviews(Request $request, String $id, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $form = $this->createForm(ReviewFormType::class);
        $reviewRepository = $entityManager->getRepository(Review::class);
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id"=>$id]);
        $reviews = $reviewRepository->findAllByMovie($movie,0,100);
        if(!$this->getUser()){
            return $this->render('movies/reviews.html.twig', [
                "movie" => $movie,
                "reviews" => $reviews,
                "form" => $form->createView(),
                "page_tabs" => 'movies_read',
                "canPost" => false,
            ] );
        }
        $previousReviews = $entityManager->getRepository(Review::class)->findAllByUserMovie($this->getUser(), $movie);
        $array  = $previousReviews->toArray();
        $form->handleRequest($request);
        $canPost = count($array) == 0;
        if ($form->isSubmitted()) {
            if($canPost){
                return $this->redirectToRoute('movies_read');
            }


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
            $embededUser->setName($this->getUser()->getName());
            $embededMovie = new EmbedMovieRef();
            $embededMovie->setMovie($movie);
            $embededMovie->setName($movie->getTitle());
            $review->setAccepted(false);
            $review->setUser($embededUser);
            $review->setMovie($embededMovie);

            if($review->getComment() == '' or $review->getComment() == null ){
                $review->setAccepted(true);
            }

            try {
                $entityManager->persist($review);
                $entityManager->flush();
            }catch (\Exception $e){
                return $this->json(['error' => true]);
            }
            return $this->redirectToRoute('movies_read_one', ["id" => $id]);
        }

        return $this->render('movies/reviews.html.twig', [
            "movie" => $movie,
            "reviews" => $reviews,
            "form" => $form->createView(),
            "page_tabs" => 'movies_read',
            "canPost" => $canPost,
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

        $genres = $entityManager->getRepository(Genre::class)->findAll();

        $form = $this->createForm(MovieFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            var_dump("TEST");
            if (!$form->isValid()) {
                $errors = $validator->validate($form->getData());
                if (count($errors) > 0) {
                    return $this->render('index/index.html.twig',[
                        "error" => $errors,
                        "response_code" => 206]);
                }
            }


            $movie = $form->getData();

            foreach ( $form->get('genres')->getData() as $key => $value)
            {
                $genre = $entityManager->getRepository(Genre::class)->find($value->getId());
                $movie->addGenre($genre);
            }

            foreach ( $form->get('lang')->getData() as $key => $value)
            {
                $lang = $entityManager->getRepository(Lang::class)->find($value->getId());
                $movie->addLang($lang);
            }

            foreach ( $form->get('countries')->getData() as $key => $value)
            {
                $country = $entityManager->getRepository(Country::class)->find($value->getId());
                $movie->addCountry($country);
            }


            foreach ( $form->get('cast')->getData() as $key => $value)
            {
                $cast = $entityManager->getRepository(Person::class)->find($value->getId());
                $movie->addCast($cast);
            }


            $requestMovie = new MovieRequest();
            $requestMovie->setMovieSubmission($movie);
            $requestMovie->setAction(EnumMovieRequestAction::ACTION_ADD);
            $requestMovie->setUser($this->getUser());
            $requestMovie->setCreated(new \DateTime());

            try {
                $entityManager->persist($movie);
                $entityManager->persist($requestMovie);
                $entityManager->flush();
            }catch (\Exception $e){
                var_dump($e->getMessage());
                return $this->render('index/index.html.twig', ['error' => $e->getMessage()]);
            }

            return $this->redirectToRoute('movies_read',['from' => 0, 'to' => 10, 'page' => 0]);
        }
        return $this->render('movies/add.html.twig', array('form' => $form->createView(), 'genres' => $genres, "page_tabs" => 'movies_read'));
    }

    /**
     * @Route("/watchlist/add/{id}", name="_watchlist_add")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function addToWatchlist(String $id, EntityManagerInterface $entityManager)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id" => $id]);
        $embedMovie = new EmbedMovieRef();
        $embedMovie->setName($movie->getTitle());
        $embedMovie->setMovie($movie);

        $watchlist = $this->getUser()->getWatchlist();
        $watchlist->getMovies()[] = $embedMovie;
        try{
            $entityManager->flush();
        } catch (\Exception $e)
        {
            return $this->render('error/error.html.twig', ["error" => $e]);
        }

        return $this->redirectToRoute('movies_read_one', ["id" => $id, "page_tabs" => 'movies_read']);

    }

    /**
     * @Route("/watchlist/remove/{id}", name="_watchlist_remove")
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function removeFromWatchlist(String $id, EntityManagerInterface $entityManager)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id" => $id]);
        $watchlist = $this->getUser()->getWatchlist();
        $watchlist->removeMovie($id);
        try{
            $entityManager->flush();
        } catch (\Exception $e)
        {
            return $this->render('error/error.html.twig', ["error" => $e]);
        }

        return $this->redirectToRoute('movies_read_one', ["id" => $id, "page_tabs" => 'movies_read']);

    }


    /**
     * @Route("/update/{id}", name="_update")
     * @param Request $request
     * @param String $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function update(Request $request, String $id,
                           EntityManagerInterface $entityManager
    )
    {
        $form = $this->createForm(MovieFormType::class);
        $form->remove("rating");
        $form->handleRequest($request);

        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id" => $id]);

        if ($form->isSubmitted()) {
            $movieUpdated = $form->getData();
            $genres = [];
            foreach ( $form->get('genres')->getData() as $key => $value)
            {
                $genres[] = $value->getName();
            }
            $movieUpdated->setGenres($genres);
            $requestMovie = new RequestMovie();
            $movieRef = new EmbedMovieRef();
            $movieRef->setMovie($movie);
            $movieRef->setName($movie->getTitle());
            $requestMovie->setMovie($movieRef);
            $requestMovie->setNewMovie($movieUpdated);
            $requestMovie->setAction(2);
            $requestMovie->setCreated(new \DateTime());

            try {
                $entityManager->persist($requestMovie);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->render('index/index.html.twig', ['error' => true]);
            }

            return $this->redirectToRoute('movies_read',['from' => 0, 'to' => 10, 'page' => 1]);
        }

        return $this->render('movies/update.html.twig',['form' => $form->createView(), 'movie' => $movie, "page_tabs" => 'movies_read']);
    }


    /**
     * @Route("/delete/{id}", name="_delete")
     * @param String $id
     * @param DocumentManager $documentManager
     * @return Response
     */
    public function delete(String $id, EntityManagerInterface $entityManager)
    {
        $requestMovie = new RequestMovie();
        $movieRef = new EmbedMovieRef();
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(["id"=>$id]);
        $movieRef->setMovie($movie);
        $movieRef->setName($movie->getTitle());
        $requestMovie->setAction(3);
        $requestMovie->setMovie($movieRef);
        $requestMovie->setCreated(new \DateTime());
        $entityManager->persist($requestMovie);
        $entityManager->flush();

        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/average/update/", name="_update_rating")
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function updateRating(EntityManagerInterface $entityManager)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }

        if($this->getUser()->getRole() == 3){
            return $this->redirectToRoute('home');
        }

        $averageRatings = $entityManager->getRepository(Review::class)->getMoviesAverageRatings();

        foreach($averageRatings as $ar) {
            $ar->movie->setRating(round($ar->averageRating,1));
        }
        try{
            $entityManager->flush();
        } catch(\Exception $e) {
            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('_user_account');

    }

    /**
     * @Route("/search/", name="_search")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function searchMovie(Request $request, EntityManagerInterface $entityManager)
    {
      $data = $request->request->get('_search');
      $movies = $entityManager->getRepository(Movie::class)->findAllByTitle($data);

      return $this->render('movies/movies.html.twig', ["movies" => $movies, "page_tabs" => 'movies_read', 'from' => 0, 'to'=>1, 'page'=>1, 'count' => 1]);
    }

}
