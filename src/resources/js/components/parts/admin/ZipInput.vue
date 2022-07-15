<template>
    <input
        class="px-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 w-4/12"
        :type="type"
        :name="name"
        :value="modelValue"
        :placeholder="placeholder"
        @input="updateValue"
        :disabled="disabled"
    />
</template>
<script setup>
const props = defineProps({
    modelValue: { type: String, required: true },
    type: { type: String, required: true },
    name: { type: String, required: true },
    disabled: { type: String },
    placeholder: { type: String, required: false },
});
const emit = defineEmits();

const updateValue = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
