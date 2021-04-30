<template>
    <div class="bg-gray-300">
        <table class="">
            <thead>
            <tr>
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

        }
    },
    methods: {
        editPackageData(tour){
            this.$emit('editPackageData', {tour, packageList: false})
        }
    }
}
</script>

<style scoped>

</style>
