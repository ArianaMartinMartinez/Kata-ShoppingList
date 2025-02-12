<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanReceiveAllProductsWithApi() {
        Product::factory(5)->create();
        
        $response = $this->get(route('apiHomeProducts'));

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneProductWithApi() {
        $product = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 5
        ]);
        $data = ['name' => 'Potatoes'];

        $response = $this->get(route('apiShowProduct', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function tests_CheckIfCanCreateNewProductWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 5
        ]);
        $data = ['name' => 'Potatoes'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfProductCreateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 3535647,
            'quantity' => '2',
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfProductCreateReturnErrorIfProductAlreadyExistsWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 2,
        ]);
        $data = ['name' => 'Potatoes'];
        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 5,
        ]);
        $data = ['message' => 'Introduced product already exists in the list'];
        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneProductWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 2,
        ]);
        $data = ['name' => 'Potatoes'];
        $response->assertStatus(201);

        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateProduct', 1), [
            'name' => 'Potatoes modified',
            'quantity' => 6,
        ]);
        $data = ['name' => 'Potatoes modified'];
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfProductUpdateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 2,
        ]);
        $data = ['name' => 'Potatoes'];
        $response->assertStatus(201);

        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateProduct', 1), [
            'name' => 3535647,
            'quantity' => '2',
        ]);
        $data = ['message' => 'Introduced data is not correct'];
        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfProductUpdateReturnErrorIfProductAlreadyExistsWithApi() {
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Potatoes',
            'quantity' => 2,
        ]);
        $data = ['name' => 'Potatoes'];
        $response->assertStatus(201)
            ->assertJsonFragment($data);
        
        $response = $this->post(route('apiStoreProduct'), [
            'name' => 'Milk',
            'quantity' => 3,
        ]);
        $data = ['name' => 'Milk'];
        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateProduct', 1), [
            'name' => 'Milk',
            'quantity' => 5,
        ]);
        $data = ['message' => 'Introduced product already exists in the list'];
        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneProductWithApi() {
        Product::factory(2)->create();

        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyProduct', 1));
        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfCanDeleteAllProductFromTheListWithApi() {
        Product::factory(2)->create();

        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyList'));
        $response = $this->get(route('apiHomeProducts'));
        $response->assertStatus(200)
            ->assertJsonCount(0);
    }
}
