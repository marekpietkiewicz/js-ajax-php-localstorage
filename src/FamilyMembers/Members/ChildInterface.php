<?php declare(strict_types=1);

namespace App\FamilyMembers\Members;

interface ChildInterface {

    public static function add(String $name, Array $cost, Array $members, \App\FamilyMembers\Guardian\Guardian $guardian): array;

}