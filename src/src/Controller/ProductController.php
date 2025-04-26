<?php

declare(strict_types=1);


namespace App\Controller;

use App\Dto\CreateProductDto;
use App\Dto\ProductsListDto;
use App\Dto\QueryParamsDto;
use App\Dto\UpdateProductDto;
use App\Entity\Product;
use App\Service\CreateProduct;
use App\Service\GetProduct;
use App\Service\UpdateProduct;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route('/api/products', name: 'products_')]
class ProductController extends AbstractController
{
    public function __construct(private readonly GetProduct $getProduct)
    {
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/products',
        description: 'Returns a paginated list of products with an indicator if another page is available.',
        summary: 'List products',
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response with list of products',
                content: new Model(type: ProductsListDto::class)
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error for query parameters',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'errors',
                            description: 'List of validation error messages',
                            type: 'array',
                            items: new OA\Items(type: 'string')
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function list(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY)] QueryParamsDto $queryParamsDto = new QueryParamsDto(
        )
    ): JsonResponse {
        return $this->json($this->getProduct->execute($queryParamsDto));
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/products/{id}',
        description: 'Returns detailed information about a single product by its ID using the "product:read" serialization group.',
        summary: 'Retrieve product details',
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product details retrieved successfully',
                content: new Model(type: Product::class, groups: ['product:read'])
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            ),
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        return $this->json($product, 200, [], ['groups' => 'product:read']);
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/products',
        summary: 'Create a new product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CreateProductDto::class))
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 201, description: 'Product created successfully', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: 'integer')
                ],
                type: 'object'
            )
            ),
            new OA\Response(response: 404, description: 'Category not found'),
            new OA\Response(response: 422, description: 'Validation failed')
        ]
    )]
    public function create(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY)] CreateProductDto $dto,
        CreateProduct $createProduct
    ): JsonResponse {
        $product = $createProduct->create($dto);

        return $this->json($product, 201, [], ['groups' => 'product:create']);
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/products/{id}',
        summary: 'Update an existing product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateProductDto')
        ),
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the product to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'Product successfully updated', content: new OA\JsonContent(
                ref: '#/components/schemas/ProductDto'
            )
            ),
            new OA\Response(response: 404, description: 'Category not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
        ]
    )]
    public function update(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY)] UpdateProductDto $dto,
        Product $product,
        UpdateProduct $updateProductService
    ): JsonResponse {
        $updatedProduct = $updateProductService->update($product, $dto);

        return $this->json($updatedProduct, 200, [], ['groups' => 'product:update']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/products/{id}',
        description: 'Delete a product.',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the product to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 123)
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Product successfully deleted'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            ),
        ]
    )]
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();

        return $this->json(null, 204);
    }
}
