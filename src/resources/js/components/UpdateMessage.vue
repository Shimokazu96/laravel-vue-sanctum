<template>
  <div
    v-show="message"
    class="rounded-lg p-4 m-auto mb-4 w-4/5 text-sm bg-green-100 text-green-700"
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
