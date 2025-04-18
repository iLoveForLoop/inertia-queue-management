import { defineStore } from "pinia";

export const usePollingStore = defineStore("polling", {
    state: () => ({
        isPaused: false,
    }),
    actions: {
        pause() {
            this.isPaused = true;
        },
        resume() {
            this.isPaused = false;
        },
    },
});
