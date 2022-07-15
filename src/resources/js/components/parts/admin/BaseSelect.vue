<template>
    <select
        class="bg-gray-50 block w-4/12 text-gray-900 border rounded-lg h-10 px-4"
        :name="name"
        @change="updateValue"
        :disabled="disabled"
    >
        <option v-if="defaultName" value="">{{ defaultName }}</option>
        <option
            v-for="(option, index) in options"
            :key="index"
            :value="option.value"
            :selected="option.value === modelValue"
        >
            {{ option.name }}
        </option>
    </select>
</template>
<script setup>
const props = defineProps({
    modelValue: { type: String, required: true },
    options: { type: String, required: true },
    name: { type: String, required: true },
    disabled: { type: String },
    defaultName: { type: String },
});
const emit = defineEmits();

const updateValue = (e) => {
    emit("update:modelValue", e.target.value);
};
</script>
