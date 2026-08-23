<?php

namespace Tests\Feature;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwitterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_unique_username(): void
    {
        $response = $this->post('/register', [
            'name' => 'Alice Doe',
            'username' => 'AliceDoe',
            'email' => 'alice@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Alice Doe',
            'username' => 'alicedoe', // lowercased
            'email' => 'alice@example.com',
        ]);

        // Duplicate username should fail
        $this->post('/logout');

        $duplicateResponse = $this->post('/register', [
            'name' => 'Another Alice',
            'username' => 'alicedoe',
            'email' => 'another@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $duplicateResponse->assertSessionHasErrors('username');
    }

    public function test_user_can_login_with_email_or_username(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        // 1. Login with username
        $response1 = $this->post('/login', [
            'login' => 'johndoe',
            'password' => 'password123',
        ]);
        $response1->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $this->post('/logout');
        $this->assertGuest();

        // 2. Login with email
        $response2 = $this->post('/login', [
            'login' => 'john@example.com',
            'password' => 'password123',
        ]);
        $response2->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_use_one_click_demo_login(): void
    {
        $user = User::factory()->create([
            'username' => 'taylorotwell',
            'name' => 'Taylor Otwell',
        ]);

        $response = $this->post('/demo-login/taylorotwell');
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_post_a_tweet(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tweets', [
            'message' => 'Hello Twitter clone! #laravel #php @taylorotwell',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('tweets', [
            'user_id' => $user->id,
            'message' => 'Hello Twitter clone! #laravel #php @taylorotwell',
        ]);
    }

    public function test_tweet_message_cannot_exceed_280_characters(): void
    {
        $user = User::factory()->create();

        $longMessage = str_repeat('a', 281);

        $response = $this->actingAs($user)->post('/tweets', [
            'message' => $longMessage,
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('tweets', 0);
    }

    public function test_author_can_edit_and_update_tweet(): void
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'Original tweet',
        ]);

        $editView = $this->actingAs($user)->get("/tweets/{$tweet->id}/edit");
        $editView->assertOk();
        $editView->assertSee('Original tweet');

        $response = $this->actingAs($user)->put("/tweets/{$tweet->id}", [
            'message' => 'Updated tweet message',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('tweets', [
            'id' => $tweet->id,
            'message' => 'Updated tweet message',
        ]);
    }

    public function test_unauthorized_user_cannot_edit_or_update_another_users_tweet(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        $tweet = Tweet::factory()->create([
            'user_id' => $author->id,
            'message' => 'Author original tweet',
        ]);

        $editView = $this->actingAs($otherUser)->get("/tweets/{$tweet->id}/edit");
        $editView->assertForbidden();

        $response = $this->actingAs($otherUser)->put("/tweets/{$tweet->id}", [
            'message' => 'Hacked tweet',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('tweets', [
            'id' => $tweet->id,
            'message' => 'Author original tweet',
        ]);
    }

    public function test_author_can_delete_their_tweet(): void
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'Tweet to be deleted',
        ]);

        $response = $this->actingAs($user)->delete("/tweets/{$tweet->id}");
        $response->assertRedirect();

        $this->assertDatabaseMissing('tweets', [
            'id' => $tweet->id,
        ]);
    }

    public function test_unauthorized_user_cannot_delete_another_users_tweet(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        $tweet = Tweet::factory()->create([
            'user_id' => $author->id,
            'message' => 'Author tweet',
        ]);

        $response = $this->actingAs($otherUser)->delete("/tweets/{$tweet->id}");
        $response->assertForbidden();

        $this->assertDatabaseHas('tweets', [
            'id' => $tweet->id,
        ]);
    }

    public function test_user_can_like_and_unlike_a_tweet(): void
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create();

        // 1. Like
        $response = $this->actingAs($user)->postJson("/tweets/{$tweet->id}/like");
        $response->assertOk();
        $response->assertJson([
            'liked' => true,
            'likes_count' => 1,
        ]);

        $this->assertTrue($tweet->isLikedBy($user));
        $this->assertDatabaseHas('tweet_likes', [
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);

        // 2. Unlike
        $unlikeResponse = $this->actingAs($user)->postJson("/tweets/{$tweet->id}/like");
        $unlikeResponse->assertOk();
        $unlikeResponse->assertJson([
            'liked' => false,
            'likes_count' => 0,
        ]);

        $this->assertFalse($tweet->isLikedBy($user));
        $this->assertDatabaseMissing('tweet_likes', [
            'user_id' => $user->id,
            'tweet_id' => $tweet->id,
        ]);
    }

    public function test_user_can_follow_and_unfollow_another_user(): void
    {
        $user = User::factory()->create(['username' => 'user1']);
        $targetUser = User::factory()->create(['username' => 'user2']);

        // 1. Follow
        $response = $this->actingAs($user)->postJson("/users/{$targetUser->id}/follow");
        $response->assertOk();
        $response->assertJson([
            'following' => true,
            'followers_count' => 1,
        ]);

        $this->assertTrue($user->isFollowing($targetUser));
        $this->assertTrue($targetUser->isFollowedBy($user));

        // 2. Unfollow
        $unfollowResponse = $this->actingAs($user)->postJson("/users/{$targetUser->id}/follow");
        $unfollowResponse->assertOk();
        $unfollowResponse->assertJson([
            'following' => false,
            'followers_count' => 0,
        ]);

        $this->assertFalse($user->isFollowing($targetUser));
    }

    public function test_user_cannot_follow_themselves(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/users/{$user->id}/follow");
        $response->assertStatus(422);

        $this->assertFalse($user->isFollowing($user));
    }

    public function test_profile_page_is_accessible_via_handle(): void
    {
        $user = User::factory()->create([
            'name' => 'Taylor Otwell',
            'username' => 'taylorotwell',
            'bio' => 'Creator of Laravel',
        ]);

        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'First tweet from Taylor',
        ]);

        $response = $this->get('/@taylorotwell');
        $response->assertOk();
        $response->assertSee('Taylor Otwell');
        $response->assertSee('@taylorotwell');
        $response->assertSee('Creator of Laravel');
        $response->assertSee('First tweet from Taylor');
    }

    public function test_profile_likes_tab_shows_liked_tweets(): void
    {
        $user = User::factory()->create(['username' => 'userlikes']);
        $author = User::factory()->create(['username' => 'author1']);

        $likedTweet = Tweet::factory()->create([
            'user_id' => $author->id,
            'message' => 'Amazing post that was liked',
        ]);

        $user->likes()->attach($likedTweet->id);

        $response = $this->get('/@userlikes?tab=likes');
        $response->assertOk();
        $response->assertSee('Amazing post that was liked');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'username' => 'myprofile',
            'bio' => 'Old bio',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'New Name',
            'bio' => 'Brand new updated bio for my Twitter clone profile.',
        ]);

        $response->assertRedirect('/@myprofile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'bio' => 'Brand new updated bio for my Twitter clone profile.',
        ]);
    }

    public function test_profile_page_returns_404_for_non_existent_user(): void
    {
        $response = $this->get('/@nonexistentuser123');
        $response->assertNotFound();
    }

    public function test_formatted_message_parses_mentions_and_hashtags_with_xss_protection(): void
    {
        $tweet = new Tweet([
            'message' => 'Check out <script>alert("xss")</script> @taylorotwell working on #laravel & #php!',
        ]);

        $formatted = $tweet->formattedMessage();

        // Ensure script tag is escaped
        $this->assertStringNotContainsString('<script>', $formatted);
        $this->assertStringContainsString('&lt;script&gt;', $formatted);

        // Ensure @mention is converted to link
        $this->assertStringContainsString('<a href="/@taylorotwell"', $formatted);
        $this->assertStringContainsString('>@taylorotwell</a>', $formatted);

        // Ensure #hashtag is converted to search link
        $this->assertStringContainsString('<a href="/?search=%23laravel"', $formatted);
        $this->assertStringContainsString('>#laravel</a>', $formatted);
    }

    public function test_search_filters_tweets_by_keyword_or_hashtag(): void
    {
        $user = User::factory()->create();

        Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'Exploring #tailwindcss design tokens',
        ]);

        Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'Just random thoughts about coffee',
        ]);

        $searchResponse = $this->get('/?search='.urlencode('#tailwindcss'));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Exploring');
        $searchResponse->assertSee('#tailwindcss');
        $searchResponse->assertDontSee('Just random thoughts about coffee');
    }

    public function test_following_feed_only_shows_tweets_from_followed_users(): void
    {
        $currentUser = User::factory()->create(['username' => 'currentuser']);
        $followedUser = User::factory()->create(['username' => 'followeduser']);
        $unfollowedUser = User::factory()->create(['username' => 'unfolloweduser']);

        $currentUser->following()->attach($followedUser->id);

        $tweetFromFollowed = Tweet::factory()->create([
            'user_id' => $followedUser->id,
            'message' => 'Tweet from followed person',
        ]);

        $tweetFromUnfollowed = Tweet::factory()->create([
            'user_id' => $unfollowedUser->id,
            'message' => 'Tweet from stranger',
        ]);

        // 1. "For you" feed shows all tweets
        $forYouResponse = $this->actingAs($currentUser)->get('/?tab=for-you');
        $forYouResponse->assertOk();
        $forYouResponse->assertSee('Tweet from followed person');
        $forYouResponse->assertSee('Tweet from stranger');

        // 2. "Following" feed shows only followed users' tweets
        $followingResponse = $this->actingAs($currentUser)->get('/?tab=following');
        $followingResponse->assertOk();
        $followingResponse->assertSee('Tweet from followed person');
        $followingResponse->assertDontSee('Tweet from stranger');
    }

    public function test_explore_page_is_accessible_and_filters_trending_topics(): void
    {
        $user = User::factory()->create();
        Tweet::factory()->create([
            'user_id' => $user->id,
            'message' => 'Exploring top trends in #laravel 12',
        ]);

        $response = $this->get('/explore');
        $response->assertOk();
        $response->assertSee('Explore');
        $response->assertSee('Trending in Tech');

        $searchResponse = $this->get('/explore?search='.urlencode('#laravel'));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Exploring');
        $searchResponse->assertSee('#laravel');
    }

    public function test_search_matches_users_by_name_or_username_or_bio(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Taylor Otwell',
            'username' => 'taylorotwell',
            'bio' => 'Creator of Laravel framework',
        ]);

        $user2 = User::factory()->create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'bio' => 'Passionate about coding',
        ]);

        // Search by username
        $response = $this->get('/explore?search=taylorotwell');
        $response->assertOk();
        $response->assertSee('People');
        $response->assertSee('Taylor Otwell');
        $response->assertSee('@taylorotwell');

        // Search with leading @
        $atResponse = $this->get('/explore?search=@taylorotwell');
        $atResponse->assertOk();
        $atResponse->assertSee('Taylor Otwell');
    }
}
