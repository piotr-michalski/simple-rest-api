<?php

declare(strict_types=1);

namespace App\Dto;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

readonly class ProductsListDto
{
    /**
     * @param ProductDto[] $data
     */
    public function __construct(
        #[OA\Property(
            property: 'data',
            description: 'Array of product items',
            type: 'array',
            items: new OA\Items(ref: new Model(type: ProductDto::class)),
        )]
        public array $data,
        #[OA\Property(
            property: 'pagination',
            ref: new Model(type: PaginationDto::class),
            description: 'Pagination information for the current result set'
        )]
        public PaginationDto $pagination
    ) {
    }
}
