<?php

namespace App\FamilyMembers;

use App\FamilyMembers\Members\Parents as Parents;
use App\FamilyMembers\Members\Animal as Animal;
use App\FamilyMembers\Members\Child as Child;
use App\FamilyMembers\Guardian\NeedsMum as NeedsMum;
use App\FamilyMembers\Guardian\NeedsParents as NeedsParents;
use App\FamilyMembers\Guardian\UniqueParent as UniqueParent;
use App\FamilyMembers\Utils as Utils;

class FamilyMembers
{
    private $members;

    public function __construct(Array $requestType, Array $jsonMembers)
    {
        $this->members = Utils::transformJsonMembers($jsonMembers);
        Utils::pickMember($this, $requestType);
    }

    public function add_mum()
    {    
        $this->jsonMembers = Parents::add("mum", 200, $this->members, new UniqueParent());
    }

    public function add_dad()
    {    
        $this->jsonMembers = Parents::add("dad", 200, $this->members, new UniqueParent());
    }

    public function add_cat()
    {    
        $this->jsonMembers = Animal::add("cat", 10, $this->members, new NeedsParents());
    }

    public function add_dog()
    {    
        $this->jsonMembers = Animal::add("dog", 15, $this->members, new NeedsParents());
    }

    public function add_goldfish()
    {    
        $this->jsonMembers = Animal::add("goldfish", 2, $this->members, new NeedsParents());
    }

    public function add_child()
    {    
        $this->jsonMembers = Child::add("children", [150, 100], $this->members, new NeedsParents());
    }

    public function adapt_child()
    {    
        $this->jsonMembers = Child::add("children", [150, 100], $this->members, new NeedsMum());
    }

    public function getMembersList()
    {    
		return ["data" => [...$this->jsonMembers], "numbers" => ["cost" => Utils::SumUpCosts($this->jsonMembers), "totalMembers" => Utils::SumUpTotalMembers($this->jsonMembers)]];
    }
}