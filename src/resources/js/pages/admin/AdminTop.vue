<template>
    <div>
        <AdminNavbar />
        <main>
            <div class="container">
                <RouterView />
            </div>
        </main>
        <AdminFooter />
    </div>
</template>

<script>
import AdminNavbar from "../../components/AdminNavbar.vue";
import AdminFooter from "../../components/AdminFooter.vue";
export default {
    components: {
        AdminNavbar,
        AdminFooter,
    },
};
</script>
