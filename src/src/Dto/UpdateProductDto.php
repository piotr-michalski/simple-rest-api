<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(schema: 'UpdateProductDto')]
class UpdateProductDto
{
    #[Assert\Length(min: 1, max: 255)]
    #[OA\Property(description: 'Product name', type: 'string', example: 'New Product Name')]
    public ?string $name = null;

    #[Assert\Type(type: 'numeric')]
    #[Assert\Positive]
    #[OA\Property(description: 'Product price', type: 'string', example: '29.99')]
    public ?string $price = null;

    /** @var string[] */
    #[Assert\All([
        new Assert\Type('string'),
        new Assert\NotBlank()
    ])]
    #[OA\Property(
        description: 'List of category codes',
        type: 'array',
        items: new OA\Items(type: 'string'),
        example: ['CATEGORY1', 'CATEGORY2']
    )]
    public array $categoryCodes = [];
}
