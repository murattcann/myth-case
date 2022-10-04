<?php

namespace App\Controller;

use App\Entity\Product;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
 
    private $em;
    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }
    #[Route('products', name: 'products.index')]
    public function index(Request $request): JsonResponse
    {
        
        $products = $this->em->getManager()->getRepository(Product::class)->getAll($request);
        $mappedData = ProductMapper::map($products);

        return $this->json($mappedData);
    }
}
