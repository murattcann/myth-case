<?php
namespace App\Mapper;

use App\Entity\Product;
use App\Response\ProductResponse;

interface IProductMapper
{
    public static function map(array $dataCollection): array;
    public static function setProductResponse(Product $product): ProductResponse;
}
