<?php
// tests/Roles/RoleTest.php
declare(strict_types=1);

namespace Tests\Roles;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Roles\Role;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

#[CoversClass(Villageois::class)]
#[CoversClass(LoupGarou::class)]
class RoleTest extends TestCase
{
    public function testVillageoisImplementsRoleInterface(): void
    {
        $villageois = new Villageois();
        
        $this->assertInstanceOf(Role::class, $villageois);
    }

    public function testLoupGarouImplementsRoleInterface(): void
    {
        $loup = new LoupGarou();
        
        $this->assertInstanceOf(Role::class, $loup);
    }

    public function testVillageoisHasCorrectName(): void
    {
        $villageois = new Villageois();
        
        $this->assertEquals('Villageois', $villageois->getName());
    }

    public function testLoupGarouHasCorrectName(): void
    {
        $loup = new LoupGarou();
        
        $this->assertEquals('Loup-Garou', $loup->getName());
    }

    public function testVillageoisIsNotWerewolf(): void
    {
        $villageois = new Villageois();
        
        $this->assertFalse($villageois->isWerewolf());
    }

    public function testLoupGarouIsWerewolf(): void
    {
        $loup = new LoupGarou();
        
        $this->assertTrue($loup->isWerewolf());
    }

    public function testVillageoisCannotActAtNight(): void
    {
        $villageois = new Villageois();
        
        $this->assertFalse($villageois->canActAtNight());
    }

    public function testLoupGarouCanActAtNight(): void
    {
        $loup = new LoupGarou();
        
        $this->assertTrue($loup->canActAtNight());
    }

    public function testVillageoisBelongsToVillageTeam(): void
    {
        $villageois = new Villageois();
        
        $this->assertEquals('village', $villageois->getTeam());
    }

    public function testLoupGarouBelongsToWerewolfTeam(): void
    {
        $loup = new LoupGarou();
        
        $this->assertEquals('werewolves', $loup->getTeam());
    }

    public function testRolesHaveDescription(): void
    {
        $villageois = new Villageois();
        $loup = new LoupGarou();
        
        $this->assertIsString($villageois->getDescription());
        $this->assertIsString($loup->getDescription());
        $this->assertNotEmpty($villageois->getDescription());
        $this->assertNotEmpty($loup->getDescription());
    }
}