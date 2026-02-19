<?php
declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Round;
use App\Players\Player;
use App\Players\PlayerCollection;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

class RoundTest extends TestCase
{
    public function testRoundCanBeCreated(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $round = new Round(1, $players);
        
        $this->assertEquals(1, $round->getNumber());
        $this->assertFalse($round->isComplete());
    }

    public function testRoundHasDayAndNightPhases(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $round = new Round(1, $players);
        
        $this->assertTrue($round->isNight());
        $this->assertFalse($round->isDay());
        
        $round->startDay();
        
        $this->assertTrue($round->isDay());
        $this->assertFalse($round->isNight());
    }

    public function testRoundTracksDeaths(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $round = new Round(1, $players);
        
        $this->assertCount(0, $round->getDeaths());
        
        $alice = $players->findByName('Alice');
        $round->addDeath($alice);
        
        $this->assertCount(1, $round->getDeaths());
        $this->assertSame($alice, $round->getDeaths()[0]);
    }

    public function testRoundCanBeCompleted(): void
    {
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
            new Player('Bob', new LoupGarou()),
        ]);
        
        $round = new Round(1, $players);
        
        $round->startDay();
        $round->complete();
        
        $this->assertTrue($round->isComplete());
    }

    public function testCannotCompleteRoundDuringNight(): void
    {
        $this->expectException(\RuntimeException::class);
        
        $players = new PlayerCollection([
            new Player('Alice', new Villageois()),
        ]);
        
        $round = new Round(1, $players);
        $round->complete(); // Ne devrait pas être possible la nuit
    }
}