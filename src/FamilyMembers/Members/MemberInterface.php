<?php declare(strict_types=1);

namespace App\FamilyMembers\Members;

interface MemberInterface {

    public function add(String $name, Int $cost, Array $members, \App\FamilyMembers\Guardian\Guardian $guardian): array;

}