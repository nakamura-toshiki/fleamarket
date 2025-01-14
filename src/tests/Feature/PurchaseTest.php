<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;
use App\Models\Buy;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**商品購入機能 */
    /**購入完了 */
    public function test_purchase_completion()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        $response = $this->post(route('storeOrder', $item->id), [
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'payment' => 'credit_card',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('buys', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);
    }

    /**購入した商品にSoldと表示 */
    public function test_item_is_marked_as_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        $this->post(route('storeOrder', $item->id), [
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'payment' => 'credit_card',
        ]);

        $response = $this->get(route('success', $item->id));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $this->assertDatabaseHas('buys', [
            'item_id' => $item->id,
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('index'));
    }

    /**プロフィール/購入した商品一覧に追加されている */
    public function test_items_purchased_displayed()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'A',
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $buy = Buy::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
            'payment' => 'カード払い',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('success', ['item_id' => $item->id]));

        $response->assertStatus(302);

        $response->assertRedirect(route('index'));

        $response = $this->get(route('index'));
        $response->assertSee('マイページ');

        $response = $this->get(route('mypage'));

        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $response->assertSee($item->name);
    }



    /**配送先変更機能 */
    /**住所の変更が商品購入画面に反映される */
    public function test_address_display_on_purchase()
    {
        DB::beginTransaction();

        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        Profile::create([
            'user_id' => $user->id,
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $this->actingAs($user);

        $this->post(route('newAddress', ['item_id' => $item->id]), [
            'zip' => '987-6543',
            'address' => '東京都新宿区4-5-6',
            'building' => '新宿マンション202',
        ]);

        $response = $this->get(route('order', ['item_id' => $item->id]));

        $response->assertSee('987-6543');
        $response->assertSee('東京都新宿区4-5-6');
        $response->assertSee('新宿マンション202');

        DB::rollBack();
    }

    /**購入した商品に送付先住所紐づけ */
    public function test_correct_address_is_linked_on_purchase()
    {
        DB::beginTransaction();

        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('storeOrder', $item->id), [
            'zip' => '987-6543',
            'address' => '東京都新宿区4-5-6',
            'payment' => 'credit_card',
        ]);

        $this->assertDatabaseHas('buys', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip' => '987-6543',
            'address' => '東京都新宿区4-5-6',
        ]);

        DB::rollBack();
    }
}
