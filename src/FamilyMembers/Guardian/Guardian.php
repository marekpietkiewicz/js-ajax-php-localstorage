<?php declare(strict_types=1);

namespace App\FamilyMembers\Guardian;

interface Guardian {

    public function check(Array $members, String $name): void;

}