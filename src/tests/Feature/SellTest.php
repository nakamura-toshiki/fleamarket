<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemCategory;

class SellTest extends TestCase
{

    use RefreshDatabase;

    /**出品商品情報登録 */
    public function test_user_sell_item()
    {
        $user = User::factory()->create();

        $categories = Category::factory(3)->create();

        $this->actingAs($user);

        $imagePath = storage_path('app/public/image/Armani+Mens+Clock.jpg');
        $image = new UploadedFile($imagePath, 'Armani+Mens+Clock.jpg', 'image/jpeg', null, true);

        $response = $this->actingAs($user)
                    ->post(route('store'), [
                        'name' => '商品名',
                        'brand' => 'ブランド名',
                        'price' => 5000,
                        'description' => '商品の説明',
                        'condition' => '良好',
                        'image' => $image,
                        'category_id' => $categories->pluck('id')->toArray(),
                    ]);

        $itemId = Item::latest()->first()->id;
        $savedImagePath = Item::latest()->first()->image;

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => '商品名',
            'brand' => 'ブランド名',
            'price' => 5000,
            'description' => '商品の説明',
            'condition' => '良好',
            'image' => $savedImagePath,
        ]);

        foreach ($categories as $category) {
            $this->assertDatabaseHas('item_categories', [
                'item_id' => $itemId,
                'category_id' => $category->id,
            ]);
        }
    }
}
