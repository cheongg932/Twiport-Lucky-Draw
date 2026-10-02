import type { DrawResult, GameId, Prize } from '@/types';

function csrfToken(): string {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
}

async function request<T>(url: string, init?: RequestInit): Promise<T> {
    const response = await fetch(url, {
        credentials: 'same-origin',
        ...init,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken(),
            ...(init?.headers ?? {}),
        },
    });

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return response.json() as Promise<T>;
}

export function fetchCatalog(): Promise<{ prizes: Prize[]; wheel: string[] }> {
    return request('/api/prizes');
}

export function drawPrize(game: GameId): Promise<DrawResult> {
    return request(`/api/draw/${game}`, { method: 'POST' });
}
