<?php

namespace App\Tests\Util;

use App\Entity\Product;
use App\Util\DiscountCalculator;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase{

    private ManagerRegistry $managerRegistry;
    private $product;
   /*  public function __construct(ManagerRegistry $mana)
    {
        
    } */
    public function setUp(): void
    {
        
        $this->product =  (new Product)
        ->setSku("000003")
        ->setName("Ashlington leather ankle boots")
        ->setCategory("boots")
        ->setPrice(71000);
    }

    public function testGetPercent(){
        $discountPercent = DiscountCalculator::make($this->product)->getPercent();
        $this->assertEquals(30, $discountPercent);
    }

    public function testGetAmount(){
        $discountedAmount = DiscountCalculator::make($this->product)->getAmount();
        $this->assertEquals(49700, $discountedAmount);
    }
}