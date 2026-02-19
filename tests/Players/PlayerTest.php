<?php
// tests/Players/PlayerTest.php
declare(strict_types=1);

namespace Tests\Players;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Players\Player;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

#[CoversClass(Player::class)]

class PlayerTest extends TestCase
{
    public function testPlayerCanBeCreatedWithNameAndRole(): void
    {
        $role = new Villageois();
        $player = new Player('Alice', $role);
        
        $this->assertEquals('Alice', $player->getName());
        $this->assertSame($role, $player->getRole());
    }

    public function testPlayerIsAliveByDefault(): void
    {
        $player = new Player('Bob', new Villageois());
        
        $this->assertTrue($player->isAlive());
        $this->assertFalse($player->isDead());
    }

    public function testPlayerCanBeKilled(): void
    {
        $player = new Player('Charlie', new Villageois());
        
        $player->kill();
        
        $this->assertFalse($player->isAlive());
        $this->assertTrue($player->isDead());
    }

    public function testDeadPlayerCannotBeKilledAgain(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Le joueur est déjà mort');
        
        $player = new Player('David', new Villageois());
        $player->kill();
        $player->kill(); // Ne devrait pas être possible
    }

    public function testPlayerCanBeProtected(): void
    {
        $player = new Player('Eve', new Villageois());
        
        $this->assertFalse($player->isProtected());
        
        $player->protect();
        
        $this->assertTrue($player->isProtected());
    }

    public function testProtectedPlayerCannotBeKilled(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Le joueur est protégé');
        
        $player = new Player('Frank', new Villageois());
        $player->protect();
        $player->kill();
    }

    public function testProtectionIsRemovedAfterNight(): void
    {
        $player = new Player('Grace', new Villageois());
        $player->protect();
        
        $player->endNight();
        
        $this->assertFalse($player->isProtected());
    }

    public function testPlayerCanBeInLove(): void
    {
        $player1 = new Player('Alice', new Villageois());
        $player2 = new Player('Bob', new LoupGarou());
        
        $this->assertFalse($player1->isInLove());
        
        $player1->setLover($player2);
        
        $this->assertTrue($player1->isInLove());
        $this->assertSame($player2, $player1->getLover());
    }

    public function testPlayerHasStringRepresentation(): void
    {
        $player = new Player('Alice', new Villageois());
        
        $this->assertStringContainsString('Alice', (string) $player);
        $this->assertStringContainsString('Villageois', (string) $player);
    }
}