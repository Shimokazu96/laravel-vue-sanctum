<template>
    <div>
        <TheHeader v-if="data.flg"/>
        <TheLogHeader v-else/>
        <main>
            <div>
                <RouterView />
            </div>
        </main>
        <TheFooter />
    </div>
</template>
<script setup>
import { onMounted, reactive } from "vue";
import TheHeader from "@/components/templates/front/TheHeader.vue";
import TheFooter from "@/components/templates/front/TheFooter.vue";
import TheLogHeader from "@/components/templates/front/TheLogHeader.vue";
const data =  reactive({ flg:false })
</script>
