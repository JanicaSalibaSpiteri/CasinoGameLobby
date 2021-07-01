<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Category;
use App\Repository\GameRepository;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\Interfaces\CacheInterface;

class GameController extends AbstractController
{
    /**
     * @Route("/", defaults={"_format"="html"}, methods="GET", name="lobby_index")
     */
    public function index(Request $request, GameRepository $games, CategoryRepository $categories, CacheInterface $cache): Response
    {
        $cache = $cache->cache;

        //Get games from cache
        $cachedGames = $cache->getItem("all_games");
        $cachedGames->expiresAfter(1800);

        if (!$cachedGames->isHit())
        {
            $allGames = $games->getGames();

            $cachedGames->set($allGames);

            $cache->save($cachedGames);
        }

        //Get categories from cache
        $cachedCats = $cache->getItem("all_categories");
        $cachedCats->expiresAfter(1800);

        if (!$cachedCats->isHit())
        {
            $allCategories = $categories->getCategories();

            $cachedCats->set($allCategories);

            $cache->save($cachedCats);
        }

        return $this->render('default/homepage.html.twig', ['games' => $cachedGames->get(), 'categories' => $cachedCats->get()]);
    }

    /**
     * @Route("/game/{gameId}", methods="GET", name="game_show")
     */
    public function getGameById(int $gameId, GameRepository $games, CacheInterface $cache): Response
    {
        $cache = $cache->cache;

        $cachedGames = $cache->getItem("all_games");
        $cachedGames->expiresAfter(1800);

        if (!$cachedGames->isHit())
        {
            $allGames = $games->getGames();

            $cachedGames->set($allGames);

            $cache->save($cachedGames);
        }

        $game = $games->getGameById($gameId, $cachedGames->get());

        return $this->render('lobby/game_show.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/games/search", methods="GET", name="games_search")
     */
    public function getGamesByName(Request $request, GameRepository $games, CacheInterface $cache): Response
    {
        $gameName = $request->query->get('search_keyword');

        $cache = $cache->cache;

        $cachedGames = $cache->getItem("all_games");
        $cachedGames->expiresAfter(1800);

        if (!$cachedGames->isHit())
        {
            $allGames = $games->getGames();

            $cachedGames->set($allGames);

            $cache->save($cachedGames);
        }

        $gamesFound = $games->searchGamesByName($gameName, $cachedGames->get());

        //Get categories from cache
        $cachedCats = $cache->getItem("all_categories");
        $cachedCats->expiresAfter(1800);

        if (!$cachedCats->isHit())
        {
            $allCategories = $categories->getCategories();

            $cachedCats->set($allCategories);

            $cache->save($cachedCats);
        }

        return $this->render('default/homepage.html.twig', ['games' => $gamesFound, 'categories' => $cachedCats->get()]);
    }

    /**
     * @Route("/games/filter", methods="GET", name="games_filter")
     */
    public function filterGamesByCategory(Request $request, GameRepository $games, CacheInterface $cache): Response
    {
        $category = $request->query->get('category');
        echo "<script>console.log('Debug Objects: " . $category . "' );</script>";

        $cache = $cache->cache;

        $cachedGames = $cache->getItem("all_games");
        $cachedGames->expiresAfter(1800);

        if (!$cachedGames->isHit())
        {
            $allGames = $games->getGames();

            $cachedGames->set($allGames);

            $cache->save($cachedGames);
        }

        $gamesFound = $games->filterGamesByCategory($category, $cachedGames->get());

        //Get categories from cache
        $cachedCats = $cache->getItem("all_categories");
        $cachedCats->expiresAfter(1800);

        if (!$cachedCats->isHit())
        {
            $allCategories = $categories->getCategories();

            $cachedCats->set($allCategories);

            $cache->save($cachedCats);
        }

        return $this->render('default/homepage.html.twig', ['games' => $gamesFound, 'categories' => $cachedCats->get()]);
    }
    }
}