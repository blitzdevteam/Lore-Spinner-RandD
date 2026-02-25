import { onUnmounted, ref } from 'vue';

const audioCache = new Map<string, HTMLAudioElement>();

export function useTextToSpeech() {
    const isPlaying = ref(false);
    const isLoading = ref(false);
    let currentAudio: HTMLAudioElement | null = null;
    let currentKey: string | null = null;

    const attachListeners = (audio: HTMLAudioElement) => {
        audio.addEventListener('canplay', () => {
            isLoading.value = false;
        });
        audio.addEventListener('playing', () => {
            isPlaying.value = true;
            isLoading.value = false;
        });
        audio.addEventListener('ended', () => {
            isPlaying.value = false;
        });
        audio.addEventListener('pause', () => {
            isPlaying.value = false;
        });
        audio.addEventListener('error', () => {
            isPlaying.value = false;
            isLoading.value = false;
        });
    };

    const play = (gameId: string, promptId: string) => {
        const key = `${gameId}:${promptId}`;

        if (currentAudio && currentKey === key && !currentAudio.ended) {
            if (currentAudio.paused) {
                currentAudio.play();
            }
            return;
        }

        stop();

        if (audioCache.has(key)) {
            currentAudio = audioCache.get(key)!;
            currentKey = key;
            currentAudio.currentTime = 0;
            isPlaying.value = true;
            currentAudio.play();
            return;
        }

        isLoading.value = true;
        const audio = new Audio(`/user/games/${gameId}/tts/${promptId}`);
        audio.preload = 'auto';
        attachListeners(audio);
        audioCache.set(key, audio);
        currentAudio = audio;
        currentKey = key;
        audio.play();
    };

    const stop = () => {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
        }
        isPlaying.value = false;
        isLoading.value = false;
    };

    const toggle = (gameId: string, promptId: string) => {
        if (isPlaying.value && currentKey === `${gameId}:${promptId}`) {
            stop();
        } else {
            play(gameId, promptId);
        }
    };

    onUnmounted(() => stop());

    return { isPlaying, isLoading, play, stop, toggle };
}
