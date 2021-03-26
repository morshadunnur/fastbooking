<template>
    <dashboard-layout>
        <template #header-nav>
            <HeaderNav/>
        </template>
        <template #sidebar>
            <Sidebar/>
        </template>
        <template #main-content>
            <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
                <div class="w-1/2 bg-gray-200 p-5">
                    <CreateCategory/>
                </div>
                <div class="w-1/2 bg-gray-200 p-5">
                    <CategoryList :categories="$page.props.categories"/>
                </div>
            </div>
        </template>
    </dashboard-layout>
</template>

<script>
import DashboardLayout from "../Layouts/DashboardLayout";
import HeaderNav from "../../Components/Partials/HeaderNav";
import Sidebar from "../../Components/Partials/Sidebar";
import CreateCategory from "../../Components/Category/CreateCategory";
import CategoryList from "../../Components/Category/CategoryList";
export default {
    name: "Index",
    components: {CategoryList, CreateCategory, Sidebar, HeaderNav, DashboardLayout},

}
</script>

<style scoped>

</style>
