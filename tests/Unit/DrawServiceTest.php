<?php

namespace Tests\Unit;

use App\Services\DrawService;
use Tests\TestCase;

class DrawServiceTest extends TestCase
{
    public function test_catalog_is_weighted_and_includes_flagship_devices(): void
    {
        $catalog = (new DrawService)->catalog();
        $ids = array_column($catalog, 'id');

        $this->assertContains('iphone-16-pro', $ids);
        $this->assertContains('macbook-air', $ids);
        $this->assertContains('airpods-pro', $ids);
        $this->assertGreaterThan(0, array_sum(array_column($catalog, 'weight')));
    }

    public function test_wheel_has_eight_segments(): void
    {
        $this->assertCount(8, (new DrawService)->wheelOrder());
    }

    public function test_a_win_on_slots_matches_all_three_reels(): void
    {
        $service = new DrawService;

        for ($i = 0; $i < 40; $i++) {
            $result = $service->draw('slots');

            if ($result['prize']['id'] !== 'miss') {
                $this->assertSame(
                    [$result['prize']['id'], $result['prize']['id'], $result['prize']['id']],
                    $result['reels'],
                );

                return;
            }
        }

        $this->assertTrue(true);
    }
}
