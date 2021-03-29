<template>
    <div class="bg-gray-300">
        <table class="">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(category, index) in categories" :key="index">
                    <td>{{ ++index }}</td>
                    <td>{{ category.name }}</td>
                    <td v-if="category.status === 1">Published</td>
                    <td v-if="category.status === 0">Draft</td>
                    <td>
                        <button>Edit</button>
                        <button @click="deleteCategory(category.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "CategoryList",
    props: {
        categories: {
            required: true,
            type: Array,
            default: []
        }
    },
    data() {
        return {
            categoryList: [],
        }
    },
    computed: {
        setCategories() {
            return this.categoryList = this.categories;
        }
    },
    methods: {
        deleteCategory(category_id){
            this.$inertia.delete(this.route('category.delete', category_id), {
                onBefore: (visit) => confirm('Are you sure to Delete category?'),
                onStart: (visit) => {
                    this.sending = true
                },
                onProgress: (progress) => {},
                onSuccess: (page) => {},
                onError: (errors) => {
                },
                onCancel: () => {},
                onFinish: () => {
                    this.sending = false;
                },
            })
        }
    },
    created() {

    }
}
</script>

<style scoped>

</style>
