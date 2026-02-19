<?php
declare(strict_types=1);

namespace App\Roles;

class Villageois implements Role
{
    public function getName(): string
    {
        return 'Villageois';
    }

    public function isWerewolf(): bool
    {
        return false;
    }

    public function canActAtNight(): bool
    {
        return false;
    }

    public function getTeam(): string
    {
        return 'village';
    }

    public function getDescription(): string
    {
        return 'Un simple villageois qui doit découvrir les loups-garous.';
    }
}
