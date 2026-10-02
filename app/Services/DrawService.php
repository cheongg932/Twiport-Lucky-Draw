<?php

namespace App\Services;

use InvalidArgumentException;

class DrawService
{
    /**
     * @return list<array<string, mixed>>
     */
    public function catalog(): array
    {
        /** @var list<array<string, mixed>> $catalog */
        $catalog = config('prizes.catalog', []);

        return $catalog;
    }

    /**
     * @return array{prize: array<string, mixed>, reels?: list<string>}
     */
    public function draw(string $game): array
    {
        if (! in_array($game, ['spin', 'scratch', 'slots'], true)) {
            throw new InvalidArgumentException("Unknown game [{$game}].");
        }

        $prize = $this->pick();
        $payload = ['prize' => $prize];

        if ($game === 'slots') {
            $payload['reels'] = $this->slotReels($prize['id']);
        }

        if ($game === 'spin') {
            $payload['segment'] = $this->wheelSegmentFor($prize['id']);
        }

        return $payload;
    }

    /**
     * @return list<string>
     */
    public function wheelOrder(): array
    {
        return [
            'iphone-16-pro',
            'airpods-pro',
            'miss',
            'watch-ultra',
            'macbook-air',
            'ipad-pro',
            'voucher',
            'miss',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function pick(): array
    {
        $catalog = $this->catalog();
        $total = (int) array_sum(array_column($catalog, 'weight'));
        $roll = random_int(1, max(1, $total));
        $running = 0;

        foreach ($catalog as $prize) {
            $running += (int) $prize['weight'];

            if ($roll <= $running) {
                return $prize;
            }
        }

        return $catalog[array_key_last($catalog)];
    }

    /**
     * @return list<string>
     */
    private function slotReels(string $prizeId): array
    {
        $ids = array_values(array_filter(
            array_column($this->catalog(), 'id'),
            fn (string $id): bool => $id !== 'miss',
        ));

        if ($prizeId !== 'miss') {
            return [$prizeId, $prizeId, $prizeId];
        }

        shuffle($ids);

        return [$ids[0], $ids[1], $ids[2] ?? $ids[0]];
    }

    private function wheelSegmentFor(string $prizeId): int
    {
        $order = $this->wheelOrder();
        $matches = [];

        foreach ($order as $index => $id) {
            if ($id === $prizeId) {
                $matches[] = $index;
            }
        }

        if ($matches === []) {
            return (int) array_search('miss', $order, true);
        }

        return $matches[array_rand($matches)];
    }
}
