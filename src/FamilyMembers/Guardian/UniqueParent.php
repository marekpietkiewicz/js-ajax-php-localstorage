<?php declare(strict_types=1);

namespace App\FamilyMembers\Guardian;

class UniqueParent implements Guardian
{    
    public function check(Array $members, String $name) : void
    {
        $key = array_search($name, array_column($members, 'type'));
        if(is_numeric($key)){
            throw new \Exception(sprintf("ERROR: The family already has a %s. (No support for modern families yet. :))", $name), 406);
        }
    }    
}