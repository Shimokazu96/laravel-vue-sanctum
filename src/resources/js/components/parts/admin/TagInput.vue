<template>
    <div>
        <VueTagsInput
            v-model="inputTag"
            :tags="selectedTags"
            @tags-changed="(newTags) => (selectedTags = newTags)"
            :autocomplete-items="filteredItems"
            @before-adding-tag="checkValidTag"
            :existing-tags="existingTags"
            :add-on-key="[13, 32]"
            :name="name"
            placeholder="タグを追加"
        />
        <div v-if="errorText" class="text-red-500">{{ errorText }}</div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import VueTagsInput from "@sipec/vue3-tags-input";
const props = defineProps({
    // 既存のタグたち
    existingTags: {
        type: Array,
        default: () => [],
    },
    // タグの初期値
    tag: {
        type: Array,
        default: () => [],
    },
    // inputのname属性
    name: {
        type: String,
        default: null,
    },
    modelValue: { type: String, default: () => [] },
});
const emit = defineEmits();

const inputTag = ref("");
const selectedTags = ref([]);
const tag = computed(() => props.tag);
const errorText = ref("");

watch(tag, () => {
    selectedTags.value = props.tag;
});
watch(selectedTags, () => {
    if (selectedTags.value !== null && selectedTags.value !== undefined) {
        emit("update:modelValue", JSON.stringify(selectedTags.value));
    }
});
const filteredItems = computed(() => {
    return props.existingTags.filter((tag) => {
        return (
            tag.text.toLowerCase().indexOf(inputTag.value.toLowerCase()) !== -1
        );
    });
});
const checkValidTag = (obj) => {
    if (obj.tag.text.length > 30) {
        errorText.value = "タグは30文字以内で入力してください";
    } else {
        errorText.value = "";
        obj.addTag();
    }
};
</script>
