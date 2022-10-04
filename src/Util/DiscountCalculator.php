<?php

namespace App\Util;

use App\Entity\Product;
use App\Enums\ProductEnum;

class DiscountCalculator
{
    private static Product $product;
    private static $categoryDiscount;
    private static $skuDiscount;
    
    public static function make(Product $product){
        self::$product = $product;
        self::$categoryDiscount = ProductEnum::CATEGORY_DISCOUNTS[self::$product->getCategory()] ?? 0;
        self::$skuDiscount = ProductEnum::SKU_DISCOUNTS[self::$product->getSku()]  ?? 0;

        return new self;
    }
    public static function getPercent(){
        
        if(self::$skuDiscount > 0 || self::$categoryDiscount > 0){
            return ceil(self::$skuDiscount > self::$categoryDiscount ? self::$skuDiscount : self::$categoryDiscount);
        }

        return 0;
    }

    public static function getAmount(){
        return ceil(self::$product->getPrice() - (self::$product->getPrice() * (self::getPercent() / 100)));
    }
}
