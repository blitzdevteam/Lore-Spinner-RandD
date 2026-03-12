import { toggle } from '@/wayfinder/actions/App/Http/Controllers/User/BookmarkController';
import { ref } from 'vue';

function getXsrfToken(): string | null {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
}

export function useBookmark(storyId: number, initialState: boolean = false) {
    const isBookmarked = ref(initialState);
    const isToggling = ref(false);

    const toggleBookmark = async () => {
        if (isToggling.value) return;

        isToggling.value = true;
        const previousState = isBookmarked.value;
        isBookmarked.value = !previousState;

        try {
            const route = toggle(storyId);
            const xsrf = getXsrfToken();

            const response = await fetch(route.url, {
                method: route.method,
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    ...(xsrf ? { 'X-XSRF-TOKEN': xsrf } : {}),
                },
            });

            if (!response.ok) {
                if (response.status === 401) {
                    isBookmarked.value = previousState;
                    window.location.href = '/user/authentication/login';
                    return;
                }
                throw new Error('Failed to toggle bookmark');
            }

            const data = await response.json();
            isBookmarked.value = data.is_bookmarked;
        } catch {
            isBookmarked.value = previousState;
        } finally {
            isToggling.value = false;
        }
    };

    return {
        isBookmarked,
        isToggling,
        toggleBookmark,
    };
}
