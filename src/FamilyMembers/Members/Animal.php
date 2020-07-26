<?php declare(strict_types=1);

namespace App\FamilyMembers\Members;

class Animal implements MemberInterface
{    
    public function add(String $name, Int $cost, Array $members, \App\FamilyMembers\Guardian\Guardian $guardian) : array
    {
        $guardian->check($members, $name);
        $searchedMember = array_search($name, array_column($members, 'type'));
        
        //there is already a searchedMember, just update one
        if(is_numeric($searchedMember)){
            $members[$searchedMember]["qty"] += 1;
            $members[$searchedMember]["cost"] =  $members[$searchedMember]["qty"] * $cost;
        //new fresh entry
        }else{
            array_push($members, ["type"=> $name, "qty"=>  1, "cost"=>  $cost]);
        }
            
        return $members;
    }    
}