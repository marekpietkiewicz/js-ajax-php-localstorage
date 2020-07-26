<?php declare(strict_types=1);

namespace App\FamilyMembers\Members;

class Child implements ChildInterface
{    
    public static function add(String $name, Array $cost, Array $members, \App\FamilyMembers\Guardian\Guardian $guardian) : array
    {
        $guardian->check($members, $name);
        $searchedMember = array_search($name, array_column($members, 'type'));
        
        //if there is already a searchedMember, just update one
        if(is_numeric($searchedMember)){
            $members[$searchedMember]["qty"] += 1;

            //are there at least three children?
            if($members[$searchedMember]["qty"] > 2){
                $members[$searchedMember]["cost"] =  (($members[$searchedMember]["qty"] - 2) * $cost[1]) + ($cost[0] * 2);
            }else{
                $members[$searchedMember]["cost"] =  $members[$searchedMember]["qty"] * $cost[0];
            }

        //new fresh entry
        }else{
            array_push($members, ["type"=> $name, "qty"=>  1, "cost"=>  $cost[0]]);
        }
        
        return $members;
    }    
}