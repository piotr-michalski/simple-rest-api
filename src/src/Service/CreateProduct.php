<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateProductDto;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

readonly class CreateProduct
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepo,
        private Notification $notification
    ) {
    }

    /**
     * @throws Throwable
     */
    public function create(CreateProductDto $dto): Product
    {
        $this->em->beginTransaction();

        try {
            $categories = [];
            foreach ($dto->categoryCodes as $categoryCode) {
                $category = $this->categoryRepo->findOneBy(['code' => $categoryCode]);
                if (!$category) {
                    throw new NotFoundHttpException("Category with code {$categoryCode} not found");
                }
                $categories[] = $category;
            }

            $product = new Product();
            $product->setName($dto->name)
                ->setPrice($dto->price);

            foreach ($categories as $category) {
                $productCategory = new ProductCategory();
                $productCategory->setCategory($category);
                $productCategory->setProduct($product);
                $product->addProductCategory($productCategory);
                $this->em->persist($productCategory);
            }

            $this->em->persist($product);
            $this->em->flush();
            $this->em->commit();

            $this->notification->send(
                sprintf(
                    'Product "%s" has been created with %d categories.',
                    $product->getName(),
                    count($product->getProductCategories())
                )
            );

            return $product;
        } catch (Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
