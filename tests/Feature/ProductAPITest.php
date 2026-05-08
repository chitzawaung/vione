<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;

class ProductAPITest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_list_products()
    {
        // Arrange
        Product::factory()->count(15)->create();

        // Act
        $response = $this->getJson('/api/products');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'quantity_available', 'in_stock', 'created_at', 'updated_at']
                ],
                'meta',
                'links'
            ])
            ->assertJson(['success' => true]);

        $this->assertEquals(15, $response->json('meta.total'));
    }

    /** @test */
    public function it_can_filter_products_by_search()
    {
        // Arrange
        Product::factory()->create(['name' => 'Coca Cola']);
        Product::factory()->create(['name' => 'Pepsi']);
        Product::factory()->create(['description' => 'Refreshing soda drink']);

        // Act
        $response = $this->getJson('/api/products?search=cola');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Coca Cola', $data[0]['name']);
    }

    /** @test */
    public function it_can_filter_products_by_stock_availability()
    {
        // Arrange
        Product::factory()->count(5)->inStock()->create();
        Product::factory()->count(3)->outOfStock()->create();

        // Act
        $response = $this->getJson('/api/products?in_stock=true');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(5, $data);

        foreach ($data as $product) {
            $this->assertTrue($product['in_stock']);
        }
    }

    /** @test */
    public function it_can_sort_products()
    {
        // Arrange
        Product::factory()->create(['name' => 'A Product', 'price' => 10.00]);
        Product::factory()->create(['name' => 'B Product', 'price' => 20.00]);
        Product::factory()->create(['name' => 'C Product', 'price' => 30.00]);

        // Act
        $response = $this->getJson('/api/products?sort_by=price&sort_direction=desc');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertEquals('C Product', $data[0]['name']);
        $this->assertEquals(30.00, $data[0]['price']);
    }

    /** @test */
    public function it_can_paginate_products()
    {
        // Arrange
        Product::factory()->count(25)->create();

        // Act
        $response = $this->getJson('/api/products?per_page=10&page=2');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total', 'from', 'to'],
                'links'
            ]);

        $meta = $response->json('meta');
        $this->assertEquals(2, $meta['current_page']);
        $this->assertEquals(10, $meta['per_page']);
    }

    /** @test */
    public function it_can_show_single_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->getJson("/api/products/{$product->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'description', 'price', 'quantity_available', 'in_stock', 'created_at', 'updated_at']
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity_available' => $product->quantity_available,
                    'in_stock' => $product->quantity_available > 0,
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_product()
    {
        // Act
        $response = $this->getJson('/api/products/999');

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resource not found'
            ]);
    }

    /** @test */
    public function it_can_create_product()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 15.99,
            'quantity_available' => 25,
        ];

        // Act
        $response = $this->postJson('/api/products', $productData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'description', 'price', 'quantity_available', 'in_stock', 'created_at', 'updated_at']
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => [
                    'name' => 'Test Product',
                    'description' => 'Test description',
                    'price' => 15.99,
                    'quantity_available' => 25,
                    'in_stock' => true,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 15.99,
            'quantity_available' => 25,
        ]);
    }

    /** @test */
    public function it_validates_product_creation()
    {
        // Arrange - Invalid data
        $productData = [
            'name' => '', // Required field missing
            'price' => -5, // Invalid price
            'quantity_available' => 'invalid', // Invalid type
        ];

        // Act
        $response = $this->postJson('/api/products', $productData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ])
            ->assertJson(['success' => false]);

        $errors = $response->json('errors');
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('price', $errors);
        $this->assertArrayHasKey('quantity_available', $errors);
    }

    /** @test */
    public function it_requires_authentication_to_create_product()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'price' => 15.99,
            'quantity_available' => 25,
        ];

        // Act
        $response = $this->postJson('/api/products', $productData);

        // Assert
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
    }

    /** @test */
    public function it_can_update_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $updateData = [
            'name' => 'Updated Product',
            'price' => 25.99,
            'quantity_available' => 50,
        ];

        // Act
        $response = $this->actingAs($this->createUser())
            ->putJson("/api/products/{$product->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => [
                    'name' => 'Updated Product',
                    'price' => 25.99,
                    'quantity_available' => 50,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 25.99,
            'quantity_available' => 50,
        ]);
    }

    /** @test */
    public function it_validates_product_update()
    {
        // Arrange
        $product = Product::factory()->create();
        $updateData = [
            'price' => -10, // Invalid price
            'quantity_available' => 'invalid', // Invalid type
        ];

        // Act
        $response = $this->actingAs($this->createUser())
            ->putJson("/api/products/{$product->id}", $updateData);

        // Assert
        $response->assertStatus(422)
            ->assertJson(['success' => false]);

        $errors = $response->json('errors');
        $this->assertArrayHasKey('price', $errors);
        $this->assertArrayHasKey('quantity_available', $errors);
    }

    /** @test */
    public function it_requires_authentication_to_update_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $updateData = ['name' => 'Updated Product'];

        // Act
        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        // Assert
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
    }

    /** @test */
    public function it_can_delete_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->actingAs($this->createUser())
            ->deleteJson("/api/products/{$product->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function it_requires_authentication_to_delete_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->deleteJson("/api/products/{$product->id}");

        // Assert
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
    }

    /** @test */
    public function it_can_get_low_stock_products()
    {
        // Arrange
        Product::factory()->count(10)->inStock()->create();
        Product::factory()->count(3)->lowStock()->create();
        Product::factory()->count(5)->outOfStock()->create();

        // Act
        $response = $this->actingAs($this->createAdminUser())
            ->getJson('/api/products/reports/low-stock');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(3, $data);

        foreach ($data as $product) {
            $this->assertLessThan(5, $product['quantity_available']);
        }
    }

    /** @test */
    public function it_requires_admin_role_for_low_stock_report()
    {
        // Act
        $response = $this->actingAs($this->createUser())
            ->getJson('/api/products/reports/low-stock');

        // Assert
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized'
            ]);
    }

    /** @test */
    public function it_can_get_out_of_stock_products()
    {
        // Arrange
        Product::factory()->count(10)->inStock()->create();
        Product::factory()->count(5)->outOfStock()->create();

        // Act
        $response = $this->actingAs($this->createAdminUser())
            ->getJson('/api/products/reports/out-of-stock');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(5, $data);

        foreach ($data as $product) {
            $this->assertEquals(0, $product['quantity_available']);
            $this->assertFalse($product['in_stock']);
        }
    }

    /** @test */
    public function it_requires_admin_role_for_out_of_stock_report()
    {
        // Act
        $response = $this->actingAs($this->createUser())
            ->getJson('/api/products/reports/out-of-stock');

        // Assert
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized'
            ]);
    }

    /** @test */
    public function it_handles_invalid_sort_field()
    {
        // Arrange
        Product::factory()->count(5)->create();

        // Act
        $response = $this->getJson('/api/products?sort_by=invalid_field');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Should default to sorting by 'id'
        $data = $response->json('data');
        $this->assertEquals(1, $data[0]['id']);
    }

    /** @test */
    public function it_limits_per_page_maximum()
    {
        // Arrange
        Product::factory()->count(200)->create();

        // Act
        $response = $this->getJson('/api/products?per_page=150');

        // Assert
        $response->assertStatus(200);
        
        $meta = $response->json('meta');
        $this->assertLessThanOrEqual(100, $meta['per_page']);
    }

    /** @test */
    public function it_handles_edge_case_zero_products()
    {
        // Act
        $response = $this->getJson('/api/products');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                    'from' => null,
                    'to' => null,
                ]
            ]);
    }

    /** @test */
    public function it_searches_in_name_and_description()
    {
        // Arrange
        Product::factory()->create(['name' => 'Cola Drink', 'description' => 'Refreshing beverage']);
        Product::factory()->create(['name' => 'Soda', 'description' => 'Not refreshing']);

        // Act
        $response = $this->getJson('/api/products?search=refreshing');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Cola Drink', $data[0]['name']);
    }

    /**
     * Helper method to create a regular user.
     */
    private function createUser()
    {
        return \App\Models\User::factory()->create(['role' => 'user']);
    }

    /**
     * Helper method to create an admin user.
     */
    private function createAdminUser()
    {
        return \App\Models\User::factory()->create(['role' => 'admin']);
    }
}
