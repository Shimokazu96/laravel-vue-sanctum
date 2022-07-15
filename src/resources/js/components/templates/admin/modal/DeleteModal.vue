<template>
    <div
        class="fixed w-full h-full bg-slate-800 bg-opacity-50 flex justify-center items-center top-0 left-0 z-40"
    >
        <div class="bg-white px-16 py-14 rounded-md text-center z-50">
            <h1 class="text-xl mb-4 font-bold text-slate-500">
                削除してもよろしいですか？
            </h1>
            <button
                class="bg-gray-500 px-4 py-2 rounded-md text-md text-white"
                type="button"
                data-bs-dismiss="modal"
                @click="closeModal()"
            >
                Cancle
            </button>
            <button
                class="bg-red-500 px-7 py-2 ml-2 rounded-md text-md text-white font-semibold"
                type="button"
                @click="deletedClick()"
            >
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    showModal: {
        type: Boolean,
        required: true,
    },
});
const emit = defineEmits();

const deletedClick = () => {
    emit("deleteFlag");
};
const closeModal = () => {
    emit("close");
};
</script>
