<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductFixtures extends Fixture
{
    private KernelInterface $kernel;
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function load(ObjectManager $manager): void
    {
        $projectRoot = $this->kernel->getProjectDir();
        $productsArray = json_decode(file_get_contents($projectRoot. "/products.json"), true);
        
        $batchSize=5;
        $i = 1;
        foreach($productsArray as $item){
            $i++;
            $product = new Product();
            $product = $product->setSku("asd")
            ->setName($item["name"])
            ->setCategory($item["category"])
            ->setPrice($item["price"]);
              
            $manager->getRepository(Product::class)->save($product, true);
             
        }
 
        /* $product = new Product();
        $product = $product->setSku("000001")
        ->setName("11"."başlık")
        ->setCategory("kategori")
        ->setPrice(2500);
        $manager->persist($product);
        $manager->flush(); */
        /* $batchSize=5;
        $i = 1;
        foreach($productsArray as $item){
            $i++;
            $product = new Product();
            $product = $product->setSku(str_replace("0", "", $item["sku"]))
            ->setName("11".$item["name"])
            ->setCategory($item["category"])
            ->setPrice($item["price"]);
            dd($product, $productsArray);
            $manager->persist($product);
            
            if(($i % $batchSize) === 0){
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
        $manager->clear(); */
    }
}
