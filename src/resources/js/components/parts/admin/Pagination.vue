<template>
    <div class="p-4">
        <ul class="pagination justify-end">
            <li
                class="inactive"
                :class="current_page == 1 ? 'd-none' : ''"
                @click="changePage(current_page - 1)"
            >
                «
            </li>
            <li
                v-for="page in frontPageRange"
                :key="page"
                @click="changePage(page)"
                :class="isCurrent(page) ? 'active' : 'inactive'"
            >
                {{ page }}
            </li>
            <li v-show="front_dot" class="inactive disabled">...</li>
            <li
                v-for="page in middlePageRange"
                :key="page"
                @click="changePage(page)"
                :class="isCurrent(page) ? 'active' : 'inactive'"
            >
                {{ page }}
            </li>
            <li v-show="end_dot" class="inactive disabled">...</li>
            <li
                v-for="page in endPageRange"
                :key="page"
                @click="changePage(page)"
                :class="isCurrent(page) ? 'active' : 'inactive'"
            >
                {{ page }}
            </li>
            <li
                class="inactive"
                :class="current_page >= last_page ? 'd-none' : ''"
                @click="changePage(current_page + 1)"
            >
                »
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const props = defineProps({
    current_page: { type: Number, required: true },
    last_page: { type: Number, required: false },
    per_page: { type: Number, required: false },
});

const range = ref(2);
const front_dot = ref(false);
const end_dot = ref(false);
const emit = defineEmits();

const calRange = (start, end) => {
    const range = [];
    for (let i = start; i <= end; i++) {
        range.push(i);
    }
    return range;
};

const frontPageRange = computed(() => {
    if (props.last_page < range.value) {
        return calRange(1, 1);
    }
    return calRange(1, 2);
});
const middlePageRange = computed(() => {
    let start = "";
    let end = "";

    if (props.last_page < range.value) {
        return;
    }
    if (props.last_page <= 3) {
        start = 3;
        end = 3;
        front_dot.value = false;
        end_dot.value = false;
        return calRange(start, end);
    }
    if (props.last_page <= 6) {
        start = 3;
        end = 4;
        front_dot.value = false;
        end_dot.value = false;
        return calRange(start, end);
    }
    if (props.current_page <= range.value) {
        start = 3;
        end = range.value + 3;
        front_dot.value = false;
        end_dot.value = true;
    }
    if (props.current_page > props.last_page - range.value) {
        start = props.last_page - range.value - 1;
        end = props.last_page - 2;
        front_dot.value = true;
        end_dot.value = false;
    } else if (props.current_page - Math.floor(range.value / 2) <= 3) {
        start = 3;
        end = range.value + 3;
        front_dot.value = false;
        end_dot.value = true;
    } else {
        start = props.current_page - Math.floor(range.value / 2);
        end = props.current_page + Math.floor(range.value / 2);
        front_dot.value = true;
        end_dot.value = true;
    }
    return calRange(start, end);
});
const endPageRange = computed(() => {
    if (props.last_page < range.value) {
        return;
    }
    if (props.last_page <= range.value + 2) {
        return;
    }
    return calRange(props.last_page - 1, props.last_page);
});
const changePage = (page) => {
    if (page > 0 && page <= props.last_page) {
        console.log(page);
        emit("changePage", route.query, page, props.per_page);
    }
};
const isCurrent = (page) => {
    return page === props.current_page;
};
</script>
<style scoped>
.pagination {
    display: flex;
    list-style-type: none;
}
.pagination li {
    border: 1px solid #ddd;
    padding: 6px 12px;
    text-align: center;
    cursor: pointer;
}
.active {
    background-color: #337ab0;
    color: white;
}
.inactive {
    color: #337ab0;
}
.pagination li + li {
    border-left: none;
}
.pagination li.disabled {
    cursor: not-allowed;
}
</style>
