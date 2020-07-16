<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class FamilyCalculatorTest extends TestCase
{
    public function testAddMum() : void
    {
        $FamilyCalculator = new FamilyCalculator();
        $res = $FamilyCalculator->addFamilyMember(    'add_mum', json_encode([])    );
        $this->assertEquals($res[0], ["type"=> "mum", "qty"=> 1, "cost"=> 200]);
    }

    public function testAddMumSecondTimeShouldFail() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $this->expectExceptionMessage("ERROR: The family already has a mum. (No support for modern families yet. :))");
        $this->FamilyCalculator->addFamilyMember(    'add_mum', json_encode([["type" => "mum", "qty" => 1, "cost" => 200]])    );
    }

    public function testAdaptChildWithMum() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $res = $this->FamilyCalculator->addFamilyMember(    'adapt_child', json_encode([["type" => "mum", "qty" => 1, "cost" => 200]])    );
        $this->assertEquals($res, [["type"=> "mum", "qty"=> 1, "cost"=> 200],["type"=> "children", "qty"=> 1, "cost"=> 150]]);
    }

    public function testAdaptChildWithoutMumShouldFail() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $this->expectExceptionMessage("ERROR: Can't adapt children without a mum.");
        $this->FamilyCalculator->addFamilyMember(    'adapt_child', json_encode([])    );
    }

    public function testNoChildrenWithoutMumOrDad() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $this->expectExceptionMessage("ERROR: No children without a mum and a dad.");
        $res = $this->FamilyCalculator->addFamilyMember(    'add_child', json_encode([])    );
    }

    public function testFirstChildCost150() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $res = $this->FamilyCalculator->addFamilyMember(    'add_child', json_encode([["type" => "mum", "qty" => 1, "cost" => 200],["type" => "dad", "qty" => 1, "cost" => 200]])    );
        $this->assertEquals($res[2]["cost"], 150);
    }

    public function testThirdChildCost400() : void
    {
        $this->FamilyCalculator = new FamilyCalculator();
        $res = $this->FamilyCalculator->addFamilyMember(    'add_child', json_encode([["type" => "mum", "qty" => 1, "cost" => 200],["type" => "dad", "qty" => 1, "cost" => 200],["type"=> "children", "qty"=> 2, "cost"=> 300]])    );
        $this->assertEquals($res[2]["cost"], 400);
    }

}
