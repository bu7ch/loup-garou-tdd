<?php
declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game;
use App\Players\Player;
use App\Players\PlayerCollection;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

class GameTest extends TestCase
{
    public function testGameCanBeCreatedWithPlayers(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new Villageois()),
            new Player('Charlie', new LoupGarou()),
        ]);
        
        $game = new Game($players);
        
        $this->assertFalse($game->isStarted());
        $this->assertFalse($game->isEnded());
    }

    public function testGameCannotStartWithLessThan6Players(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Minimum 6 joueurs requis');
        
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $game = new Game($players);
        $game->start();
    }

    public function testGameCanBeStarted(): void
    {
        $players = $this->createMinimumPlayers();
        $game = new Game($players);
        
        $game->start();
        
        $this->assertTrue($game->isStarted());
        $this->assertEquals(1, $game->getCurrentRoundNumber());
    }

    public function testGameTracksRounds(): void
    {
        $players = $this->createMinimumPlayers();
        $game = new Game($players);
        
        $game->start();
        $game->nextRound();
        
        $this->assertEquals(2, $game->getCurrentRoundNumber());
    }

    public function testGameEndsWhenWinnerDetected(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $game = new Game($players);
        $game->start();
        
        // Tuer le villageois
        $players->findByName('Alice')->kill();
        
        $this->assertTrue($game->isEnded());
        $this->assertEquals('werewolves', $game->getWinner());
    }

    public function testCannotStartGameTwice(): void
    {
        $this->expectException(\RuntimeException::class);
        
        $players = $this->createMinimumPlayers();
        $game = new Game($players);
        
        $game->start();
        $game->start(); // Ne devrait pas être possible
    }

    public function testGameReturnsAlivePlayers(): void
    {
        $players = $this->createMinimumPlayers();
        $game = new Game($players);
        
        $this->assertCount(6, $game->getAlivePlayers());
        
        $players->findByName('Alice')->kill();
        
        $this->assertCount(5, $game->getAlivePlayers());
    }

    public function testGameReturnsCurrentRound(): void
    {
        $players = $this->createMinimumPlayers();
        $game = new Game($players);
        $game->start();
        
        $round = $game->getCurrentRound();
        
        $this->assertNotNull($round);
        $this->assertEquals(1, $round->getNumber());
    }

    private function createMinimumPlayers(): PlayerCollection
    {
        return new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new Villageois()),
            new Player('Charlie', new Villageois()),
            new Player('David', new Villageois()),
            new Player('Eve', new LoupGarou()),
            new Player('Frank', new LoupGarou()),
        ]);
    }
}