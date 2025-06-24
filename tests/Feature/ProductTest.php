<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário para autenticação nos testes
        $this->user = User::factory()->create();
        // Cria outro usuário para testar permissões
        $this->otherUser = User::factory()->create();
    }

    /** @test */
    public function guests_cannot_view_products()
    {
        $this->get(route('products.index'))->assertRedirect('/login');
        $this->get(route('products.create'))->assertRedirect('/login');
        $this->get(route('products.show', Product::factory()->create()))->assertRedirect('/login');
        $this->get(route('products.edit', Product::factory()->create()))->assertRedirect('/login');
        $this->post(route('products.store'), [])->assertRedirect('/login');
        $this->put(route('products.update', Product::factory()->create()), [])->assertRedirect('/login');
        $this->delete(route('products.destroy', Product::factory()->create()))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_the_products_index_page()
    {
        $this->actingAs($this->user)
             ->get(route('products.index'))
             ->assertStatus(200)
             ->assertSee('Lista de Produtos');
    }

    /** @test */
    public function authenticated_user_can_create_a_product()
    {
        $this->actingAs($this->user);

        $productData = [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'stock' => $this->faker->numberBetween(1, 100),
        ];

        $response = $this->post(route('products.store'), $productData);

        $response->assertStatus(302); // Redireciona após salvar
        $response->assertSessionHas('success', 'Produto criado com sucesso!');
        $this->assertDatabaseHas('products', [
            'name' => $productData['name'],
            'user_id' => $this->user->id, // Verifica se o user_id foi salvo
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_a_single_product()
    {
        $product = Product::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
             ->get(route('products.show', $product->id))
             ->assertStatus(200)
             ->assertSee($product->name)
             ->assertSee(number_format($product->price, 2, ',', '.'));
    }

    /** @test */
    public function product_creator_can_update_their_product()
    {
        $product = Product::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);

        $updatedData = [
            'name' => 'Produto Atualizado',
            'description' => 'Descrição atualizada',
            'price' => 99.99,
            'stock' => 50,
        ];

        $response = $this->put(route('products.update', $product->id), $updatedData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Produto atualizado com sucesso!');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Produto Atualizado']);
    }

    /** @test */
    public function non_product_creator_cannot_update_a_product()
    {
        $product = Product::factory()->create(['user_id' => $this->otherUser->id]);
        $this->actingAs($this->user); // Este usuário não criou o produto

        $updatedData = [
            'name' => 'Tentativa de Atualização',
            'description' => 'Desc',
            'price' => 1.00,
            'stock' => 1,
        ];

        $response = $this->put(route('products.update', $product->id), $updatedData);

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseMissing('products', ['id' => $product->id, 'name' => 'Tentativa de Atualização']);
    }

    /** @test */
    public function product_creator_can_delete_their_product()
    {
        $product = Product::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Produto excluído com sucesso!');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function non_product_creator_cannot_delete_a_product()
    {
        $product = Product::factory()->create(['user_id' => $this->otherUser->id]);
        $this->actingAs($this->user); // Este usuário não criou o produto

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseHas('products', ['id' => $product->id]); // Verifica que não foi excluído
    }

    /** @test */
    public function product_creation_requires_valid_data()
    {
        $this->actingAs($this->user);

        // Testando validação de campos obrigatórios e tipo
        $response = $this->post(route('products.store'), [
            'name' => '', // Inválido: vazio
            'price' => 'abc', // Inválido: não numérico
            'stock' => -5, // Inválido: negativo
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'stock']);
    }
}