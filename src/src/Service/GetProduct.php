<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CategoryDto;
use App\Dto\PaginationDto;
use App\Dto\ProductDto;
use App\Dto\ProductsListDto;
use App\Dto\QueryParamsDto;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\ProductRepository;

readonly class GetProduct
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function execute(QueryParamsDto $queryParamsDto): ProductsListDto
    {
        $repo = $this->productRepository;

        $qb = $repo->createQueryBuilder('p')
            ->leftJoin('p.productCategories', 'pc')
            ->leftJoin('pc.category', 'c')
            ->addSelect('pc', 'c');

        $results = $qb
            ->setFirstResult(($queryParamsDto->page - 1) * $queryParamsDto->limit)
            ->setMaxResults($queryParamsDto->limit + 1)
            ->getQuery()
            ->getResult();

        $products = array_slice($results, 0, $queryParamsDto->limit);

        $productDtos = array_map(
            static function (Product $product): ProductDto {
                $categories = array_map(
                    static function (ProductCategory $productCategory): CategoryDto {
                        $category = $productCategory->getCategory();
                        return new CategoryDto(
                            id: $category->getId(),
                            code: $category->getCode(),
                            createdAt: $category->getCreatedAt(),
                            updatedAt: $category->getUpdatedAt()
                        );
                    },
                    $product->getProductCategories()->toArray()
                );

                return new ProductDto(
                    id: $product->getId(),
                    name: $product->getName(),
                    price: $product->getPrice(),
                    createdAt: $product->getCreatedAt(),
                    updatedAt: $product->getUpdatedAt(),
                    categories: $categories
                );
            },
            $products
        );

        $pagination = new PaginationDto(
            page: $queryParamsDto->page,
            limit: $queryParamsDto->limit,
            hasNext: count($results) > $queryParamsDto->limit
        );

        return new ProductsListDto($productDtos, $pagination);
    }
}
