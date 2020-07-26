<?php declare(strict_types=1);

namespace App\FamilyMembers\Members;

class Parents implements MemberInterface
{    
    public function add(String $name, Int $cost, Array $members, \App\FamilyMembers\Guardian\Guardian $guardian) : array
    {
        $guardian->check($members, $name);
        array_push($members, ["type"=> $name, "qty"=>  1, "cost"=>  $cost]);
            
        return $members;
    }    
}