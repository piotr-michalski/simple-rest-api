<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UpdateProductDto;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

readonly class UpdateProduct
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepo
    ) {
    }

    /**
     * @throws Throwable
     */
    public function update(Product $product, UpdateProductDto $dto): Product
    {
        $this->em->beginTransaction();

        try {
            if ($dto->name !== null) {
                $product->setName($dto->name);
            }

            if ($dto->price !== null) {
                $product->setPrice($dto->price);
            }

            if (!empty($dto->categoryCodes)) {
                foreach ($product->getProductCategories() as $productCategory) {
                    $this->em->remove($productCategory);
                }
                $this->em->flush();

                foreach ($dto->categoryCodes as $categoryCode) {
                    $category = $this->categoryRepo->findOneBy(['code' => $categoryCode]);
                    if (!$category) {
                        throw new NotFoundHttpException("Category with code {$categoryCode} not found");
                    }

                    $productCategory = new ProductCategory();
                    $productCategory->setProduct($product);
                    $productCategory->setCategory($category);
                    $this->em->persist($productCategory);
                }
            }

            $this->em->flush();
            $this->em->commit();

            return $product;
        } catch (Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
