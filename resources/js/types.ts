export type PrizeKind = 'iphone' | 'macbook' | 'ipad' | 'watch' | 'airpods' | 'voucher' | 'miss';
export type GameId = 'spin' | 'scratch' | 'slots';
export type Rarity = 'legendary' | 'epic' | 'rare' | 'common';

export interface Prize {
    id: string;
    name: string;
    tagline: string;
    value: string;
    kind: PrizeKind;
    rarity: Rarity;
    weight: number;
    accent: string;
}

export interface DrawResult {
    prize: Prize;
    reels?: string[];
    segment?: number;
}
