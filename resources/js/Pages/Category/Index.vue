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
                    <CreateCategory @loadCategory="getCategories"/>
                </div>
                <div class="w-1/2 bg-gray-200 p-5">
                    <CategoryList :categories="categories" @loadCategory="getCategories"/>
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
    data(){
        return {
            categories: [],
        }
    },
    methods: {
        getCategories() {
            axios.get(this.route('category.list.data'))
            .then(response => {
                if (response.status === 200){
                    console.log(response.data);
                    this.categories = response.data;
                }
            })
            .catch(error => {
                console.log(error.message);
            })
        }
    },
    created() {
        this.getCategories();
    }

}
</script>

<style scoped>

</style>
