<?php
namespace App\Mapper;

use App\Entity\Product;
use App\Response\ProductResponse;
use App\Util\DiscountCalculator;
use App\Enums\ProductEnum;

class ProductMapper implements IProductMapper
{
    /**
     * This method is mapping given collection to display for listing
     * @param array $dataCollection
     * @return array
     */
    public static function map(array $dataCollection): array{
        
        $products = [];

        foreach($dataCollection as $product){
            $products[] = self::setProductResponse($product)->toArray();
        } 

        return $products;
    }    


    /**
     * This method sets product detail data to map for listing areas
     * @param Product $product
     * @return ProductResponse
     */
    public static function setProductResponse(Product $product): ProductResponse{
        
        $response = new ProductResponse();

        $originalPrice =  $product->getPrice();
        $discountPercentage = DiscountCalculator::make($product)->getPercent();
        $finalPrice = DiscountCalculator::make($product)->getAmount();
        
        $response->setSku($product->getSku());
        $response->setName($product->getName());
        $response->setCategory($product->getCategory());
        $response->setPrice([
                "original" => $originalPrice,
                "final" => $finalPrice,
                "discount_percentage" => $discountPercentage > 0 ? $discountPercentage. "%" : null,
                "currency" => ProductEnum::DEFAULT_CURRENCY,
        ]);

        return $response;
    }
}
