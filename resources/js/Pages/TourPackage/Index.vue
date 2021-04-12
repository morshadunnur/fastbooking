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
                <div class="w-full bg-gray-200 p-5" v-if="showCreateForm">

                    <CreatePackage/>
                </div>
                <div class="w-full bg-green-100-200 p-5" v-if="showPackageList">
                    <div class="flex">
                        <h5>Package List</h5>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="changeFormVisibility">Create Package</button>
                    </div>
                    <PackageList/>
                </div>

            </div>
        </template>
    </dashboard-layout>
</template>

<script>
import DashboardLayout from "../Layouts/DashboardLayout";
import HeaderNav from "../../Components/Partials/HeaderNav";
import Sidebar from "../../Components/Partials/Sidebar";
import CreatePackage from "../../Components/Tourpackage/CreatePackage";
import PackageList from "../../Components/Tourpackage/PackageList";
export default {
    name: "Index",
    components: {PackageList, CreatePackage, Sidebar, HeaderNav, DashboardLayout},
    data(){
        return {
            showCreateForm: false,
            showPackageList: true,
        }
    },
    methods: {
        changeFormVisibility(){
            this.showCreateForm = true;
            this.showPackageList = false;
        }
    }
}
</script>

<style scoped>

</style>
