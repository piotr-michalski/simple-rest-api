<?php

namespace App\Tests\Service;

use App\Dto\CreateProductDto;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\CategoryRepository;
use App\Service\CreateProduct;
use App\Service\Notification;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CreateProductTest extends TestCase
{
    /**
     * @throws Throwable
     * @throws Exception
     */
    public function testCreateProductSuccessfully(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $categoryRepoMock = $this->createMock(CategoryRepository::class);
        $notificationMock = $this->createMock(Notification::class);

        $categoryMock = $this->createMock(Category::class);
        $categoryMock->setCode('cat1');
        $categoryRepoMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($categoryMock);

        $product = new Product();
        $product->setName('Test Product')->setPrice(100);

        $productCategory = new ProductCategory();
        $productCategory->setProduct($product);
        $productCategory->setCategory($categoryMock);
        $product->addProductCategory($productCategory);

        $entityManagerMock->expects($this->once())
            ->method('beginTransaction');
        $entityManagerMock->expects($this->once())
            ->method('flush');
        $entityManagerMock->expects($this->once())
            ->method('commit');
        $notificationMock->expects($this->once())
            ->method('send')
            ->with($this->stringContains('Product "Test Product" has been created with 1 categories.'));

        $createProductService = new CreateProduct(
            $entityManagerMock,
            $categoryRepoMock,
            $notificationMock
        );

        $dto = new CreateProductDto();
        $dto->name = 'Test Product';
        $dto->price = 100;
        $dto->categoryCodes = ['cat1'];

        $createdProduct = $createProductService->create($dto);

        $this->assertInstanceOf(Product::class, $createdProduct);
        $this->assertEquals('Test Product', $createdProduct->getName());
    }

    /**
     * @throws Throwable
     * @throws Exception
     */
    public function testCreateProductWithCategoryNotFound(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $categoryRepoMock = $this->createMock(CategoryRepository::class);
        $notificationMock = $this->createMock(Notification::class);

        $categoryRepoMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $createProductService = new CreateProduct(
            $entityManagerMock,
            $categoryRepoMock,
            $notificationMock
        );

        $dto = new CreateProductDto();
        $dto->name = 'Test Product';
        $dto->price = 100;
        $dto->categoryCodes = ['invalidCatCode'];

        $this->expectException(NotFoundHttpException::class);
        $createProductService->create($dto);
    }

}
