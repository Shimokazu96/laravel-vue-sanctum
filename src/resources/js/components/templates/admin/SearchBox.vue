<template>
    <div class="px-4 block sm:flex items-center md:divide-x md:divide-gray-100">
        <SearchInput
            type="text"
            placeholder="キーワード検索"
            v-model="searchKeyward"
            @input="updateValue"
        />

        <div
            :class="props.link ? 'sm:justify-end' : 'sm:justify-start'"
            class="flex items-center justify-between w-full"
        >
            <div class="block md:flex space-x-1">
                <SearchButton
                    name="こだわり検索"
                    @click.prevent="openModal()"
                />
            </div>
            <CreateButton
                v-if="props.link"
                name="ユーザー登録"
                :link="props.link"
            />
        </div>
    </div>
</template>
<script setup>
import SearchButton from "@/components/parts/admin/button/SearchButton.vue";
import SearchInput from "@/components/parts/admin/SearchInput.vue";
import CreateButton from "@/components/parts/admin/button/CreateButton.vue";
const props = defineProps({
    modelValue: { type: String, required: true },
    link: { type: String },
});

const emit = defineEmits();
const updateValue = (e) => {
    emit("update:modelValue", e.target.value);
};
const openModal = async () => {
    emit("openSearchModal", true);
};
</script>
