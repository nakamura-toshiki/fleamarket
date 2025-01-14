<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;

class ShowTest extends TestCase
{

    use RefreshDatabase;

    /**情報表示
     *複数選択されたカテゴリの表示
    */
    public function test_item_show()
    {
        $item = Item::factory()->create();

        $categories = Category::factory(2)->create();
        $item->categories()->attach($categories);

        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'comment' => 'This is a great item!',
        ]);

        $response = $this->get(route('show', $item->id));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->condition);
        $response->assertSee($item->description);

        foreach ($categories as $category){
            $response->assertSee($category->content);
        }

        $response->assertSee($comment->comment);
        $commentCount = $item->comments->count();
        $response->assertSee($commentCount);

        $response->assertDontSee('ユーザー名');
        $response->assertDontSee('プロフィール画像');

        $response->assertSee($item->likes_count);
    }

    /**いいね機能 */
    /**いいね押下 */
    public function test_like_increases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->assertFalse($item->isLikedByUser());
        $this->assertEquals(0, $item->likes()->count());

        $response = $this->postJson(route('like', $item->id));

        $response->assertStatus(200)
                ->assertJson([
                    'liked' => true,
                    'likesCount' => 1,
                ]);

        $this->assertTrue($item->fresh()->isLikedByUser());
        $this->assertEquals(1, $item->fresh()->likes()->count());
    }

    /**いいね解除 */
    public function test_unlike_decreases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $item->likes()->create(['user_id' => $user->id]);

        $this->assertTrue($item->isLikedByUser());
        $this->assertEquals(1, $item->likes()->count());

        $response = $this->postJson(route('like', $item->id));

        $response->assertStatus(200)
                ->assertJson([
                    'liked' => false,
                    'likesCount' => 0,
                ]);

        $this->assertFalse($item->fresh()->isLikedByUser());
        $this->assertEquals(0, $item->fresh()->likes()->count());
    }



    /**コメント機能 */
    /**コメント送信成功の場合 */
    public function test_comment_save_and_comment_count()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment', $item->id), [
            'comment' => 'This is a test comment.',
        ]);

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment.',
        ]);

        $this->assertEquals(1, $item->fresh()->comments()->count());

        $response->assertRedirect(route('show', $item->id));
    }

    /**ログインしていない場合 */
    public function test_comment_by_guest()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comment', $item->id), [
            'comment' => 'This is a test comment.',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect('/login');
    }

    /**コメントが256文字以上で送信した場合 */
    public function test_comment_validation_fails_for_exceeding_maximum_length()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment', $item->id), [
            'comment' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['comment']);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    /**コメント未入力で送信した場合 */
    public function test_comment_validation_fails_for_empty_input()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment', $item->id), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
