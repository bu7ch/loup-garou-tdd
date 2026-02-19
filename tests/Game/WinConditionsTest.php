<?php
declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\WinConditions;
use App\Players\Player;
use App\Players\PlayerCollection;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

class WinConditionsTest extends TestCase
{
    public function testNoWinnerWhenBothTeamsHavePlayers(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $winner = WinConditions::check($players);
        
        $this->assertNull($winner);
    }

    public function testWerewolvesWinWhenEqualNumbers(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
            new Player('Charlie', new LoupGarou()),
        ]);
        
        // 1 villageois vs 2 loups = égalité ou plus de loups
        $winner = WinConditions::check($players);
        
        $this->assertEquals('werewolves', $winner);
    }

    public function testVillageWinsWhenNoWerewolves(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new Villageois()),
        ]);
        
        $winner = WinConditions::check($players);
        
        $this->assertEquals('village', $winner);
    }

    public function testWerewolvesWinWhenNoVillagers(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new LoupGarou()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $winner = WinConditions::check($players);
        
        $this->assertEquals('werewolves', $winner);
    }

    public function testDeadPlayersAreNotCounted(): void
    {
        $alice = new Player('Alice', new Villageois());
        $bob = new Player('Bob', new Villageois());
        $charlie = new Player('Charlie', new LoupGarou());
        $charlie->kill(); // Le loup est mort
        
        $players = new PlayerCollection([$alice, $bob, $charlie]);
        
        $winner = WinConditions::check($players);
        
        $this->assertEquals('village', $winner);
    }

    public function testLoversWinCondition(): void
    {
        $alice = new Player('Alice', new Villageois());
        $bob = new Player('Bob', new LoupGarou());
        
        $alice->setLover($bob);
        $bob->setLover($alice);
        
        $players = new PlayerCollection([$alice, $bob]);
        
        // Les amoureux gagnent s'ils sont les deux derniers vivants
        $this->assertTrue(WinConditions::loversWin($players));
    }

    public function testLoversDoNotWinIfOthersAlive(): void
    {
        $alice = new Player('Alice', new Villageois());
        $bob = new Player('Bob', new LoupGarou());
        $charlie = new Player('Charlie', new Villageois());
        
        $alice->setLover($bob);
        $bob->setLover($alice);
        
        $players = new PlayerCollection([$alice, $bob, $charlie]);
        
        $this->assertFalse(WinConditions::loversWin($players));
    }
}