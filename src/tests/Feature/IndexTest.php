<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**商品一覧取得 */
    /**全商品を取得 */
    public function test_all_items()
    {
        $items = Item::factory()->count(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /**購入済み商品はSold表示 */
    public function test_purchased_items()
    {
        $unpurchasedItems = Item::factory()->count(3)->create();
        $purchasedItems = Item::factory()->count(2)->create(['is_sold' => true]);

        $response = $this->get('/');

        foreach ($purchasedItems as $item) {
            $response->assertSee('Sold');
        }
    }

    /**自分が出品した商品の非表示 */
    public function test_own_items_are_not_displayed()
    {
        $user = User::factory()->create(['id' => 1]);

        $otherItems = Item::factory()->count(3)->create(['user_id' => 2]);

        $userItems = Item::factory()->count(2)->create(['user_id' => $user->id]);

        Profile::create([
            'user_id' => $user->id,
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        foreach ($otherItems as $item) {
            $response->assertSee($item->name);
        }

        foreach ($userItems as $item) {
            $response->assertDontSee($item);
        }
    }



    /**マイリスト一覧取得 */
    /**いいねした商品のみ表示 */
    public function test_mylist()
    {
        $user = User::factory()->create();
        $likedItems = Item::factory()->count(3)->create();
        $user->likes()->attach($likedItems->pluck('id'));

        Profile::create([
            'user_id' => $user->id,
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        foreach ($likedItems as $item) {
            $response->assertSee($item->name);
        }
    }

    /**購入済み商品はSold表示 */
    public function test_purchased_items_in_mylist()
    {
        $user = User::factory()->create();
        $purchasedItems = Item::factory()->count(2)->create(['is_sold' => true]);

        Profile::create([
            'user_id' => $user->id,
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $user->likes()->attach($purchasedItems->pluck('id'));

        $response = $this->actingAs($user)->get('/?tab=mylist');

        foreach ($purchasedItems as $item) {
            $response->assertSee('Sold');
        }
    }

    /**未認証の場合 */
    public function test_guest_users_mylist()
    {
        $likedItems = Item::factory()->count(3)->create();

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee('<div class="content-items">');
    }



    /**商品検索機能 */
    /**部分一致検索 */
    public function test_search_displays_matching_items()
    {
        $matchingItems = Item::factory()->count(2)->create(['name' => 'matching']);
        $nonMatchingItems = Item::factory()->count(2)->create(['name' => 'unrelated']);

        $response = $this->get(route('index', ['name' => 'match']));

        foreach ($matchingItems as $item) {
            $response->assertSee($item->name);
        }

        foreach ($nonMatchingItems as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /**検索状態をマイリストでも保持 */
    public function test_search_keyword_is_retained_on_mylist_page()
    {
        $user = User::factory()->create();
        $matchingItems = Item::factory()->count(2)->create(['name' => 'matching']);

        Profile::create([
            'user_id' => $user->id,
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $response = $this->actingAs($user)->get(route('index', ['name' => 'match', 'tab' => 'recommend']));
        $response->assertSee("match");

        $user->likes()->attach($matchingItems->pluck('id'));

        $response = $this->actingAs($user)->get(route('index', ['name' => 'match', 'tab' => 'mylist']));
        $response->assertSee("match");
    }
}
