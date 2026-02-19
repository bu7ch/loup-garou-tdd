<?php
declare(strict_types=1);

namespace App\Players;

use App\Roles\Role;

class Player {
    private string $name;
    private Role $role;
    public function __construct(string $name, Role $role)
    {
        $this->name = $name;
        $this->role = $role;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
}