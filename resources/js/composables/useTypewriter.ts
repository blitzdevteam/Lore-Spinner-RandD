import { onUnmounted, ref } from 'vue';

export function useTypewriter() {
    const displayedHtml = ref('');
    const isTyping = ref(false);
    let timer: ReturnType<typeof setTimeout> | null = null;

    const start = (html: string, charsPerTick = 3, tickMs = 12) => {
        cancel();
        displayedHtml.value = '';
        isTyping.value = true;

        let index = 0;

        const tick = () => {
            if (index >= html.length) {
                isTyping.value = false;
                return;
            }

            let chunk = '';
            let remaining = charsPerTick;

            while (remaining > 0 && index < html.length) {
                if (html[index] === '<') {
                    const closeIdx = html.indexOf('>', index);
                    if (closeIdx !== -1) {
                        chunk += html.substring(index, closeIdx + 1);
                        index = closeIdx + 1;
                        continue;
                    }
                }

                if (html[index] === '&') {
                    const semiIdx = html.indexOf(';', index);
                    if (semiIdx !== -1 && semiIdx - index < 10) {
                        chunk += html.substring(index, semiIdx + 1);
                        index = semiIdx + 1;
                        remaining--;
                        continue;
                    }
                }

                chunk += html[index];
                index++;
                remaining--;
            }

            displayedHtml.value += chunk;
            timer = setTimeout(tick, tickMs);
        };

        tick();
    };

    const complete = (html: string) => {
        cancel();
        displayedHtml.value = html;
        isTyping.value = false;
    };

    const cancel = () => {
        if (timer) {
            clearTimeout(timer);
            timer = null;
        }
        isTyping.value = false;
    };

    const skipToEnd = (html: string) => {
        cancel();
        displayedHtml.value = html;
    };

    onUnmounted(() => cancel());

    return { displayedHtml, isTyping, start, complete, cancel, skipToEnd };
}
