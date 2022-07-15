<template>
    <div class="sm:pr-3 mb-4 sm:mb-0">
        <label for="products-search" class="sr-only">Search</label>
        <div class="mt-1 relative sm:w-64 xl:w-96">
            <input
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                @input="updateValue"
            />
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    modelValue: { type: String, required: true },
    type: { type: String, required: true },
    placeholder: { type: String, required: false },
});
const emit = defineEmits();

const updateValue = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
