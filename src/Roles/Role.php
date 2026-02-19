<?php
declare(strict_types=1);

namespace App\Roles;

interface Role {
    public function getName(): string;
    public function isWerewolf(): bool;
    public function canActAtNight(): bool;
    public function getTeam(): string;
    public function getDescription(): string;
}