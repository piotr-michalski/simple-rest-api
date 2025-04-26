<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

class ProductDto
{
    public function __construct(
        #[OA\Property(property: 'id', description: 'Unique identifier of the product', type: 'integer', example: 123)]
        public int $id,
        #[OA\Property(property: 'name', description: 'Name of the product', type: 'string', example: 'Widget')]
        public string $name,
        #[OA\Property(property: 'price', description: 'Price of the product', type: 'string', example: '19.99')]
        public string $price,
        #[OA\Property(property: 'createdAt', description: 'When the product was created', type: 'string', format: 'date-time', example: '2025-04-26T12:34:56+00:00')]
        public DateTimeImmutable $createdAt,
        #[OA\Property(property: 'updatedAt', description: 'Last update timestamp', type: 'string', format: 'date-time', example: '2025-04-27T08:00:00+00:00')]
        public DateTimeImmutable $updatedAt,
        /** @var CategoryDto[] */
        #[OA\Property(
            property: 'categories',
            description: 'Categories to which this product belongs',
            type: 'array',
            items: new OA\Items(ref: new Model(type: CategoryDto::class))
        )]
        public readonly array $categories,
    ) {
    }
}
