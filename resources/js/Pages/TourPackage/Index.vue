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
                    <div class="flex justify-end items-center">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            @click="changeFormVisibility">Package List
                        </button>
                    </div>
                    <CreatePackage @loadPackages="getPackages"/>
                </div>
                <div class="w-full bg-green-100-200 p-5" v-if="showPackageList">
                    <div class="flex justify-between items-center">
                        <h5>Package List</h5>
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            @click="changeFormVisibility">Create Package
                        </button>
                    </div>
                    <PackageList :packageList="packages" @loadPackages="getPackages" @editPackageData="editPackage"/>
                </div>

                <div class="w-full bg-green-100-200 p-5" v-if="showEditPackage">
                    <div class="flex justify-between items-center">
                        <h5>Update Tour Package</h5>
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            @click="changeFormVisibility">Package List
                        </button>
                    </div>
                    <EditPackage/>
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
import EditPackage from "../../Components/Tourpackage/EditPackage";

export default {
    name: "Index",
    components: {EditPackage, PackageList, CreatePackage, Sidebar, HeaderNav, DashboardLayout},
    data() {
        return {
            showCreateForm: false,
            showPackageList: true,
            showEditPackage: false,
            packages: [],
        }
    },
    methods: {
        changeFormVisibility() {
            this.showCreateForm = !this.showCreateForm;
            this.showPackageList = !this.showPackageList;
            if (this.showEditPackage){
                this.showPackageList = true;
                this.showCreateForm = false;
                this.showEditPackage = false;
            }
        },
        getPackages() {
            axios.get(this.route('tour.package.data'))
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data);
                        this.packages = response.data.packages;
                    }
                })
                .catch(error => {
                    console.log(error.message);
                })
        },
        editPackage(data) {
            this.showPackageList = data.packageList;
            this.showEditPackage = true;
        }
    },
    created() {
        this.getPackages();
    }
}
</script>

<style scoped>

</style>
