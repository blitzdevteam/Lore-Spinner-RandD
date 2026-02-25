import { onUnmounted, ref } from 'vue';

function getXsrfToken(): string | null {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
}

export function useSpeechToText() {
    const isRecording = ref(false);
    const isTranscribing = ref(false);
    const error = ref<string | null>(null);

    let mediaRecorder: MediaRecorder | null = null;
    let audioChunks: Blob[] = [];
    let stream: MediaStream | null = null;

    const startRecording = async () => {
        error.value = null;

        try {
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        } catch {
            error.value = 'Microphone access denied.';
            return;
        }

        audioChunks = [];

        const mimeType = getSupportedMimeType();
        mediaRecorder = new MediaRecorder(stream, { mimeType });

        mediaRecorder.addEventListener('dataavailable', (event: BlobEvent) => {
            if (event.data.size > 0) {
                audioChunks.push(event.data);
            }
        });

        mediaRecorder.start(250);
        isRecording.value = true;
    };

    const stopRecording = (): Promise<string> => {
        return new Promise((resolve) => {
            if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                isRecording.value = false;
                resolve('');
                return;
            }

            mediaRecorder.addEventListener(
                'stop',
                async () => {
                    isRecording.value = false;
                    releaseStream();

                    const mimeType = mediaRecorder?.mimeType || 'audio/webm';
                    const audioBlob = new Blob(audioChunks, { type: mimeType });
                    audioChunks = [];

                    if (audioBlob.size < 500) {
                        resolve('');
                        return;
                    }

                    isTranscribing.value = true;

                    try {
                        const text = await transcribe(audioBlob);
                        resolve(text);
                    } catch {
                        error.value = 'Transcription failed.';
                        resolve('');
                    } finally {
                        isTranscribing.value = false;
                    }
                },
                { once: true },
            );

            mediaRecorder.stop();
        });
    };

    const transcribe = async (audioBlob: Blob): Promise<string> => {
        const ext = audioBlob.type.includes('webm') ? 'webm' : audioBlob.type.includes('mp4') ? 'mp4' : 'webm';

        const formData = new FormData();
        formData.append('audio', audioBlob, `recording.${ext}`);

        const headers: Record<string, string> = {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        const xsrf = getXsrfToken();
        if (xsrf) {
            headers['X-XSRF-TOKEN'] = xsrf;
        }

        const response = await fetch('/user/games/transcribe', {
            method: 'POST',
            credentials: 'same-origin',
            headers,
            body: formData,
        });

        if (!response.ok) {
            const body = await response.text().catch(() => '');
            console.error('[STT] Transcription request failed:', response.status, body);
            throw new Error(`Transcription failed: ${response.status}`);
        }

        const data = await response.json();
        return data.text ?? '';
    };

    const cancelRecording = () => {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
        isRecording.value = false;
        isTranscribing.value = false;
        releaseStream();
        audioChunks = [];
    };

    const releaseStream = () => {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }
    };

    const getSupportedMimeType = (): string => {
        const types = ['audio/webm;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/ogg;codecs=opus'];
        for (const type of types) {
            if (MediaRecorder.isTypeSupported(type)) return type;
        }
        return 'audio/webm';
    };

    onUnmounted(() => {
        cancelRecording();
    });

    return { isRecording, isTranscribing, error, startRecording, stopRecording, cancelRecording };
}
