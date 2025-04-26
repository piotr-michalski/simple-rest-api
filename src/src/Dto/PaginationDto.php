<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

class PaginationDto
{
    public function __construct(
        #[OA\Property(
            description: 'Current page number.',
            type: 'integer',
            example: 1
        )]
        public int $page,

        #[OA\Property(
            description: 'Number of items per page.',
            type: 'integer',
            example: 10
        )]
        public int $limit,

        #[OA\Property(
            description: 'Indicates if there is a next page.',
            type: 'boolean',
            example: true
        )]
        public bool $hasNext
    ) {
    }
}
