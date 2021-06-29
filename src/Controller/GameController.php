<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Category;
use App\Repository\GameRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="lobby_index")
     */
    public function index(Request $request, int $page, GameRepository $games): Response
    {
        $allGames = $games->getGames($page);
        return $this->render('default/homepage.html.twig', ['games' => $allGames]);
    }

    /**
     * @Route("/game/{gameId}", methods="GET", name="game_show")
     */
    public function getGameById(int $gameId, GameRepository $games): Response
    {
        $game = $games->getGameById($gameId);

        return $this->render('lobby/game_show.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/games", methods="GET", name="games_search")
     */
    //public function getGameById(Request $request): Response //Game $game
    public function getGamesByName(Request $request, GameRepository $games): Response
    {
        $gameName = $request->query->get('search_keyword');

        $gamesFound = $games->searchGamesByName($gameName);

        return $this->render('default/homepage.html.twig', ['games' => $gamesFound]);
    }
}