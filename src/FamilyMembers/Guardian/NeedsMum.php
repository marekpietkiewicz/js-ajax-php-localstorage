<?php declare(strict_types=1);

namespace App\FamilyMembers\Guardian;

class NeedsMum implements Guardian
{    
    public function check(Array $members, String $name) : void
    {
        $mum = array_search('mum', array_column($members, 'type'));
        if(!is_numeric($mum)){
            throw new \Exception(sprintf("ERROR: Can't adapt %s without a mum.", $name), 406);
        }
    }    
}