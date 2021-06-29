<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Category;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\PropertyInfo\Extractor;
use Symfony\Component\Serializer;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return Game[]
     */
    public function getGames(int $page = 1): array //Paginator
    {
        $content = file_get_contents('..\data\games.json');
        $games = json_decode($content, true);
        $gamesList = array();

        foreach($games as $result)
        {
            $game = new Game();
            $game->setId($result['id']);
            $game->setName($result['name']);
            $game->setImage($result['icon_2']);
            $game->setProvider($result['provider_title']);

            $gameCategories = array();
            foreach($result['cats'] as $gameCategory)
            {
                $category = new Category();
                $category->setId($gameCategory['id']);
                $category->setName($gameCategory['title']);

                $gameCategories[] = $category;
            }
            $game->setCategories(...$gameCategories);

            $gamesList[] = $game;
        }

        return $gamesList;
    }

    public function getGameById(int $gameId) : Game
    {
        $gameFound = null;
        $allGames = $this->getGames();

        foreach ($allGames as $game)
        {
            if ($game->getId() == $gameId) {
                $gameFound = $game;
                break;
            }
        }

        return $gameFound;
    }

    /**
     * @return Game[]
     */
    public function searchGamesByName(string $gameName) : array
    {
        $gamesList = array();

        if ($gameName === "")
        {
            return $gamesList;
        }
        else
        {
            $allGames = $this->getGames();

            foreach ($allGames as $game)
            {
                if (strpos(strtolower($game->getName()), strtolower($gameName)) !== false)
                {
                    $gamesList[] = $game;
                }
            }

            return $gamesList;
        }
    }
}