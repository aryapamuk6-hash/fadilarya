<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatSendTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_even_when_broadcast_is_unavailable(): void
    {
        config()->set('broadcasting.default', 'reverb');

        $buyer = User::factory()->create([
            'role' => 'user',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $store = Store::create([
            'user_id' => $seller->id,
            'name' => 'Toko Test',
            'slug' => 'toko-test',
            'description' => 'Store description',
            'logo' => null,
            'rating' => 0,
            'total_sales' => 0,
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $product = Product::create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'name' => 'Laptop Test',
            'slug' => 'laptop-test',
            'description' => 'Deskripsi produk',
            'price' => 1500000,
            'stock' => 5,
            'sold_count' => 0,
            'image' => 'products/test.jpg',
            'status' => 'active',
            'is_hidden' => false,
        ]);

        $conversation = Conversation::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($buyer)
            ->post(route('chat.send', $conversation), [
                'message' => 'Halo seller',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $buyer->id,
            'message' => 'Halo seller',
        ]);
    }
}
