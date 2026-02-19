<?php
declare(strict_types=1);

namespace Tests\Actions;

use PHPUnit\Framework\TestCase;
use App\Actions\Vote;
use App\Players\Player;
use App\Roles\Villageois;
use App\Roles\LoupGarou;

class VoteTest extends TestCase
{
    public function testVoteCanBeCreated(): void
    {
        $vote = new Vote();
        
        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertCount(0, $vote->getVotes());
    }

    public function testPlayerCanVoteForAnother(): void
    {
        $vote = new Vote();
        $voter = new Player('Alice', new Villageois());
        $target = new Player('Bob', new Villageois());
        
        $vote->cast($voter, $target);
        
        $this->assertEquals($target, $vote->getTarget($voter));
    }

    public function testPlayerCannotVoteTwice(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Le joueur a déjà voté');
        
        $vote = new Vote();
        $voter = new Player('Alice', new Villageois());
        $target1 = new Player('Bob', new Villageois());
        $target2 = new Player('Charlie', new Villageois());
        
        $vote->cast($voter, $target1);
        $vote->cast($voter, $target2); // Ne devrait pas être possible
    }

    public function testDeadPlayerCannotVote(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Les morts ne peuvent pas voter');
        
        $vote = new Vote();
        $voter = new Player('Alice', new Villageois());
        $voter->kill();
        $target = new Player('Bob', new Villageois());
        
        $vote->cast($voter, $target);
    }

    public function testCannotVoteForDeadPlayer(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Impossible de voter pour un mort');
        
        $vote = new Vote();
        $voter = new Player('Alice', new Villageois());
        $target = new Player('Bob', new Villageois());
        $target->kill();
        
        $vote->cast($voter, $target);
    }

    public function testMajorityIsCalculatedCorrectly(): void
    {
        $vote = new Vote();
        
        $alice = new Player('Alice', new Villageois());
        $bob = new Player('Bob', new Villageois());
        $charlie = new Player('Charlie', new Villageois());
        $david = new Player('David', new Villageois());
        
        // 3 votes contre Bob, 1 contre Alice
        $vote->cast($alice, $bob);
        $vote->cast($charlie, $bob);
        $vote->cast($david, $bob);
        $vote->cast($bob, $alice);
        
        $result = $vote->getResult();
        
        $this->assertSame($bob, $result->getEliminated());
        $this->assertEquals(3, $result->getVoteCount());
    }

    public function testTieReturnsNull(): void
    {
        $vote = new Vote();
        
        $alice = new Player('Alice', new Villageois());
        $bob = new Player('Bob', new Villageois());
        $charlie = new Player('Charlie', new Villageois());
        $david = new Player('David', new Villageois());
        
        // 2 votes chacun = égalité
        $vote->cast($alice, $bob);
        $vote->cast($charlie, $bob);
        $vote->cast($david, $alice);
        $vote->cast($bob, $alice);
        
        $result = $vote->getResult();
        
        $this->assertNull($result);
    }

    public function testVoteCanBeReset(): void
    {
        $vote = new Vote();
        $voter = new Player('Alice', new Villageois());
        $target = new Player('Bob', new Villageois());
        
        $vote->cast($voter, $target);
        $vote->reset();
        
        $this->assertCount(0, $vote->getVotes());
    }
}