<template>
    <select
        class="block w-2/12 bg-grey-lighter text-grey-darker border border-grey-lighter rounded-lg h-10 px-4 md:w-2/12"
        name="per_page"
        v-model="per_page"
        @change="changePerPage"
    >
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
import { ref } from "vue";
import { useRoute } from "vue-router";
const route = useRoute();

const props = defineProps({
    per_page: { type: Number, required: false },
});
const emit = defineEmits();
const options = [
    { name: "10件", value: 10 },
    { name: "30件", value: 30 },
    { name: "50件", value: 50 },
];
const per_page = ref(props.per_page);

const changePerPage = () => {
    emit("changeData", route.query, 1, per_page.value);
};
</script>
