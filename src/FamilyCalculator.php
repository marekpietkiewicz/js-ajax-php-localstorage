<?php declare(strict_types=1);

class FamilyCalculator {
    
    private $FamilyCost = [
        'mum' => 200,
        'dad' => 200,
        'children' => [150, 100],
        'cat' => 10,
        'dog' => 15,
        'goldfish' => 2,
    ];
    
    public $AllowedActions = [
        ['action' => 'add_mum', 		'member' => 'mum'],
        ['action' => 'add_dad', 		'member' => 'dad'],
        ['action' => 'add_child', 		'member' => 'children'],
        ['action' => 'adapt_child', 	'member' => 'children'],
        ['action' => 'add_cat', 		'member' => 'cat'],
        ['action' => 'add_dog', 		'member' => 'dog'],
        ['action' => 'add_goldfish', 	'member' => 'goldfish']
    ];
 
    public function __construct() {
		//nothhing special to do at the moment
    }
 
    private function getMemberName(string $action): string{
        $key =  array_search($action, array_column($this->AllowedActions, 'action'));
        if(is_numeric($key)){
            return $this->AllowedActions[$key]['member'];
        }else{
            return '';
        }
    }
    
    public function addFamilyMember(string $searchFor, string $members): array{
        $elements = json_decode($members, true);
        switch($searchFor){
            case "add_dad":
            case "add_mum":
                $key = array_search($this->getMemberName($searchFor), array_column($elements, 'type'));
                if(is_numeric($key)){
                    throw new Exception(sprintf("ERROR: The family already has a %s. (No support for modern families yet. :))", $this->getMemberName($searchFor)), 123);
                }else{
                    array_push($elements, ["type"=> $this->getMemberName($searchFor), "qty"=>  1, "cost"=>  $this->FamilyCost[$this->getMemberName($searchFor)]]);
                }
            break;
            case "add_cat":
            case "add_dog":
            case "add_goldfish":
                $mum = array_search('mum', array_column($elements, 'type'));
                $dad = array_search('dad', array_column($elements, 'type'));
                $searchedMember = array_search($this->getMemberName($searchFor), array_column($elements, 'type'));

                if(!is_numeric($mum) || !is_numeric($dad)){
                    throw new Exception(sprintf("ERROR: No %s without a mum and a dad.", $this->getMemberName($searchFor)));
                }else{
                    //there is already a searchedMember, just update one
                    if(is_numeric($searchedMember)){
                        $elements[$searchedMember]["qty"] += 1;
                        $elements[$searchedMember]["cost"] =  $elements[$searchedMember]["qty"] * $this->FamilyCost[$this->getMemberName($searchFor)];

                    //new fresh entry
                    }else{
                        array_push($elements, ["type"=> $this->getMemberName($searchFor), "qty"=>  1, "cost"=>  $this->FamilyCost[$this->getMemberName($searchFor)]]);
                    }
                }
            break;
            case "add_child":
            case "adapt_child":
                $mum = array_search('mum', array_column($elements, 'type'));
                $dad = array_search('dad', array_column($elements, 'type'));
                $searchedMember = array_search($this->getMemberName($searchFor), array_column($elements, 'type'));
                
                if($searchFor == "adapt_child" && !is_numeric($mum)){
                    throw new Exception(sprintf("ERROR: Can't adapt %s without a mum.", $this->getMemberName($searchFor)));
                }
                else if($searchFor == "add_child" && (!is_numeric($mum) || !is_numeric($dad))){
                    throw new Exception(sprintf("ERROR: No %s without a mum and a dad.", $this->getMemberName($searchFor)));
                }else{
                    //if there is already a searchedMember, just update one
                    if(is_numeric($searchedMember)){
                        $elements[$searchedMember]["qty"] += 1;

                        //are there at least three children?
                        if($elements[$searchedMember]["qty"] > 2){
                            $elements[$searchedMember]["cost"] =  (($elements[$searchedMember]["qty"] - 2) * $this->FamilyCost[$this->getMemberName($searchFor)][1]) + ($this->FamilyCost[$this->getMemberName($searchFor)][0] * 2);
                        }else{
                            $elements[$searchedMember]["cost"] =  $elements[$searchedMember]["qty"] * $this->FamilyCost[$this->getMemberName($searchFor)][0];
                        }

                    //new fresh entry
                    }else{
                        array_push($elements, ["type"=> $this->getMemberName($searchFor), "qty"=>  1, "cost"=>  $this->FamilyCost[$this->getMemberName($searchFor)][0]]);
                    }
                }
            break;
        }
        return $elements;
    }

    public function SumUpTotalMembers(array $members): int{
        return array_sum(array_column($members, 'qty'));
    }

    public function SumUpCosts(array $members): int{
        return array_sum(array_column($members, 'cost'));
    }
    
    
}