<?php

namespace App\FamilyMembers;

class Utils
{
    public static function transformJsonMembers($members){
        if(is_array($members) && isset($members['data']) && is_string($members['data']) && is_array(json_decode($members['data'], true)) && (json_last_error() == JSON_ERROR_NONE)){
            return json_decode($members['data'], true);
        }else{
            throw new \Exception("Sorry, bad request. Incorrect JSON file.", 400);
        }
    }

    public static function pickMember($source, $requestType)
    {    
        if(is_array($requestType) && $requestType['action'] && method_exists($source, $requestType['action'])){
            call_user_func([$source, $requestType['action']]);
        } else{
            throw new \Exception("Sorry, bad request. The type of action not found.", 400);
        }
    }

    public static function SumUpTotalMembers(array $members): int{
        return array_sum(array_column($members, 'qty'));
    }

    public static function SumUpCosts(array $members): int{
        return array_sum(array_column($members, 'cost'));
    }
}