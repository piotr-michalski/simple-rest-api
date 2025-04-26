<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use OpenApi\Attributes as OA;

class CategoryDto
{
    public function __construct(
        #[OA\Property(property: 'id', description: 'Unique identifier of the category', type: 'integer', example: 5)]
        public int $id,
        #[OA\Property(property: 'code', description: 'Unique code for the category', type: 'string', example: 'ELEC')]
        public string $code,
        #[OA\Property(property: 'createdAt', description: 'When the category was created', type: 'string', format: 'date-time', example: '2025-04-26T12:34:56+00:00')]
        public DateTimeImmutable $createdAt,
        #[OA\Property(property: 'updatedAt', description: 'Last update timestamp', type: 'string', format: 'date-time', example: '2025-04-27T08:00:00+00:00')]
        public DateTimeImmutable $updatedAt,
    ) {
    }
}
