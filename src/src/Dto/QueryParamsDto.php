<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

readonly class QueryParamsDto
{
    public function __construct(
        #[OA\Property(
            description: 'Optional. Current page number.',
            type: 'integer',
            default: 1,
            minimum: 1,
            example: 1
        )]
        #[Assert\GreaterThanOrEqual(1, message: 'Page must be greater than or equal to 1.')]
        public int $page = 1,

        #[OA\Property(
            description: 'Optional. Number of items per page.',
            type: 'integer',
            default: 10,
            maximum: 10,
            minimum: 1,
            example: 10
        )]
        #[Assert\Range(
            notInRangeMessage: 'Limit must be between 1 and 10.',
            min: 1,
            max: 10
        )]
        public int $limit = 10
    ) {
    }
}
