<?php
// src/Roles/LoupGarou.php
declare(strict_types=1);

namespace App\Roles;

class LoupGarou implements Role
{
    public function getName(): string
    {
        return 'Loup-Garou';
    }

    public function isWerewolf(): bool
    {
        return true;
    }

    public function canActAtNight(): bool
    {
        return true;
    }

    public function getTeam(): string
    {
        return 'werewolves';
    }

    public function getDescription(): string
    {
        return 'Un loup-garou qui dévore les villageois la nuit.';
    }
}
