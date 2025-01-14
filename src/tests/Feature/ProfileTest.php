<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Buy;

class ProfileTest extends TestCase
{

    use RefreshDatabase;

    /**ユーザー情報取得 */
    public function test_user_can_view_profile()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'A',
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'ハイツ渋谷101',
        ]);

        $sellItems = Item::factory(3)->create(['user_id' => $user->id]);
        $buyItems = Item::factory(2)->create();
        foreach ($buyItems as $item) {
            Buy::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'status' => 'completed',
                'zip' => '123-4567',
                'address' => '東京都渋谷区1-2-3',
                'building' => 'ハイツ渋谷101',
                'payment' => 'カード払い',
            ]);
        }

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);

        $response->assertSee(asset('storage/' . $profile->image));
        $response->assertSee($user->name);

        foreach ($sellItems as $item) {
            $response->assertSee($item->name);
        }

        $response = $this->get(route('mypage', ['tab' => 'buy']));
        $response->assertStatus(200);

        foreach ($buyItems as $item) {
            $response->assertSee($item->name);
        }
    }

    /**ユーザー情報変更 */
    public function test_profile_data_update()
    {
        $user = User::factory()->create([
            'name' => 'A',
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'image' => 'profile.jpg',
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'image' => 'profile.jpg',
            'zip' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
        ]);

        $this->assertEquals('123-4567', $user->profile->zip);
        $this->assertEquals('東京都渋谷区1-2-3', $user->profile->address);

        $response->assertSee(asset('storage/' . $profile->image));
        $response->assertSee($user->name);
    }
}
