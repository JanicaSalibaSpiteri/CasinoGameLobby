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
    public function getGames(): array
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

    public function getGameById(int $gameId, array $allGames) : Game
    {
        $gameFound = null;

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
    public function searchGamesByName(string $gameName, array $allGames) : array
    {
        $gamesList = array();

        if ($gameName === "")
        {
            return $gamesList;
        }
        else
        {
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

    /**
     * @return Game[]
     */
    public function filterGamesByCategory(string $category, array $allGames) : array
    {
        $gamesList = array();

        if ($category === "")
        {
            return $gamesList;
        }
        else
        {
            foreach ($allGames as $game)
            {
                foreach($game->getCategories() as $cat)
                {
                    if (strpos(strtolower($cat->getName()), strtolower($category)) !== false)
                    {
                        $gamesList[] = $game;
                    }
                }
            }

            return $gamesList;
        }
    }
}