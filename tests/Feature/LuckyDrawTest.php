<?php

namespace Tests\Feature;

use Tests\TestCase;

class LuckyDrawTest extends TestCase
{
    public function test_the_spa_shell_renders_on_every_game_route(): void
    {
        foreach (['/', '/spin', '/scratch', '/slots'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('id="app"', false)
                ->assertSee('Twiport Lucky Draw');
        }
    }

    public function test_the_prize_catalog_includes_iphone_rewards(): void
    {
        $this->getJson('/api/prizes')
            ->assertOk()
            ->assertJsonStructure(['prizes', 'wheel'])
            ->assertJsonFragment(['id' => 'iphone-16-pro', 'name' => 'iPhone 16 Pro']);
    }

    public function test_spin_draw_returns_a_prize_and_segment(): void
    {
        $this->postJson('/api/draw/spin')
            ->assertOk()
            ->assertJsonStructure([
                'prize' => ['id', 'name', 'kind', 'value'],
                'segment',
            ]);
    }

    public function test_slot_draw_returns_three_reels(): void
    {
        $response = $this->postJson('/api/draw/slots')->assertOk();

        $response->assertJsonStructure(['prize', 'reels']);
        $this->assertCount(3, $response->json('reels'));
    }

    public function test_unknown_games_are_rejected(): void
    {
        $this->postJson('/api/draw/bingo')->assertUnprocessable();
    }
}
