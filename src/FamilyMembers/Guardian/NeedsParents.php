<?php declare(strict_types=1);

namespace App\FamilyMembers\Guardian;

class NeedsParents implements Guardian
{    
    public function check(Array $members, String $name) : void
    {
        $mum = array_search('mum', array_column($members, 'type'));
        $dad = array_search('dad', array_column($members, 'type'));

        if(!is_numeric($mum) || !is_numeric($dad)){
            throw new \Exception(sprintf("ERROR: No %s without a mum and a dad.", $name), 406);
        }
    }    
}