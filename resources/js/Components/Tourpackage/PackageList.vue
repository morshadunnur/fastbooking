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
            </tr>
            </thead>
            <tbody>
            <tr v-for="(tour, index) in packages" :key="index">
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
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
export default {
    name: "PackageList",
    props: {
        packages: {
            type: Array,
            required: true,
            default: []
        },

    }
}
</script>

<style scoped>

</style>
