<template>
    <div class="bg-gray-300">
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="deleteAllPackageData"
            v-if="selectAll"
        >all delete
        </button>
        <table class="">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" v-model="selectAll" @click="select">
                </th>
                <th>Serial</th>
                <th>Title</th>
                <th>Category Name</th>
                <th>Place Name</th>
                <th>Duration</th>
                <th>Descriptions</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(tour, index) in packageList" :key="index">
                <td>
                    <input type="checkbox" :value="tour.id" v-model="selected" @click="singleSelect(tour)">
                </td>
                <td>{{ ++index }}</td>
                <td>
                    {{ tour.title }}
                </td>
                <td>
                    {{ tour.category.name }}
                </td>
                <td>
                    {{ tour.place_name }}
                </td>
                <td >
                    {{ tour.duration }}
                </td>
                <td>
                    {{ tour.descriptions }}
                </td>
                <td>
                    <button class="p-4 border-2 border-gray-100 rounded" @click="editPackageData(tour)">Edit</button>
                    <button class="p-4 border-2 border-gray-100 rounded" @click="deletePackageData(tour)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
export default {
    name: "PackageList",
    props: {
        packageList: {
            type: Array,
            required: true,
            default: []
        },

    },
    data() {
        return {
            selectAll: false,
            selected: [],
        }
    },
    methods: {
        editPackageData(tour){
            this.$emit('editPackageData', {tourPackage:tour, showPackageList: false})
        },
        deletePackageData(tour){
            this.$emit('deletePackageData', {tourPackage:tour, showPackageList: true})
        },
        deleteAllPackageData(){

        },
        select(){
            this.selected = [];
                if(!this.selectAll){

                    for (let i in this.packageList){
                        this.selected.push(this.packageList[i].id);
                    }
                }
        },
        singleSelect(tour){
            this.selected.push(tour.id);
        }
    }
}
</script>

<style scoped>

</style>
