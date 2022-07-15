<template>
    <div
        class="flex bg-green-100 rounded-lg p-4 mb-4 text-sm text-green-700"
        role="alert"
        v-if="message"
    >
        {{ message }}
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const message = computed(() => store.state.message.content);
</script>
