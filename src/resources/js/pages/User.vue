<template>
  <div
    class="
      flex
      items-center
      justify-center
      h-screen
      bg-gradient-to-br bg-gray-100
    "
  >
    <div
      class="
        w-full
        bg-white
        font-semibold
        text-center
        rounded-3xl
        border
        shadow-lg
        p-10
        max-w-xs
      "
    >
      <img
        class="mb-3 w-32 h-32 rounded-full shadow-lg mx-auto"
        src="/images/human.jpeg"
        alt="product designer"
      />
      <h1 class="text-lg text-gray-700">{{ user.name }}</h1>
      <h3 class="text-sm text-gray-400">Creative Director</h3>
      <div class="flex justify-center items-center mt-4 text-gray-700">
        <p class="px-2 text-sm">Email</p>
        <p class="text-xs text-gray-400">{{ user.email }}</p>
      </div>
      <RouterLink
        class="
          block
          bg-blue-600
          px-8
          py-2
          mt-8
          rounded-3xl
          text-gray-100
          font-semibold
          uppercase
          tracking-wide
        "
        :to="`/user/${user.id}`"
        >View More
      </RouterLink>
      <RouterLink
        class="
          block
          bg-blue-600
          px-8
          py-2
          mt-8
          rounded-3xl
          text-gray-100
          font-semibold
          uppercase
          tracking-wide
        "
        :to="`/user/${user.id}/password`"
        >Setting
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const user = computed(() => store.state.user.user);

</script>
