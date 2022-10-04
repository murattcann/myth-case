<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAll(Request $request, $filters = []){
        $page  = $request->query->get("page") ?? 1;
        $limit = 3; 
         
        $queryBuilder = $this->createQueryBuilder("p");
        
        if($request->query->has("category")){
            $queryBuilder->where("p.category = :category")
            ->setParameter("category", $request->query->get("category"));
        }

        if($request->query->has("priceLessThan")){
            $queryBuilder->andWhere("p.price < :lessThanPrice")
            ->setParameter("lessThanPrice", $request->query->get("priceLessThan"));
        }
        
        $products = $queryBuilder->getQuery()
        ->setFirstResult($limit * (  $page-1))
        ->setMaxResults($limit)->getResult(); 
        return $products;
        /* $result = $queryBuilder->select("*")->from(Product::class, "product")->where("category=:categoryName")->setParameter("categoryName", "boots")->getQuery()->getResult();
        dd(1,$result); */
    }

    public function bulkAddition(array $products){
      
        foreach($products as $key=>$product){
             $entity = new Product;
           
             $entity->setName($product["name"]);
             $entity->setSku(str_replace('0','', $product["sku"]));
             $entity->setCategory($product["category"]);
             $entity->setPrice($product["price"]);
             $this->getEntityManager()->persist($entity);

             if (($key % 5) == 0) {
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
           }
        }
       
        
        $this->getEntityManager()->flush();
    }
//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
