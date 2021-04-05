<template>
    <div class="bg-gray-300">
        <table class="">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Select</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(category, index) in categories" :key="index">
                    <td>{{ ++index }}</td>
                    <td><input type="checkbox" v-model="categoryIds" :value="category.id" :key="index"></td>
                    <td :key="index">
                        <div v-if="showEditForm != category.id">{{ category.name }}</div>
                        <div v-if="showEditForm === category.id">
                            <input type="text" v-model="editForm.name = category.name">
                        </div>
                    </td>
                    <td v-if="category.status === 1">
                        Published
                    </td>
                    <td v-if="category.status === 0">
                        Draft
                    </td>
                    <td>
                        <button v-if="showEditForm != category.id" @click="showEditForm = category.id">Edit</button>
                        <button v-if="showEditForm === category.id" @click="editCategory(category.id);">Edit</button>

                        <button @click="deleteCategory(category.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <button @click="deleteAllCategory">Delete Selected</button>
        </div>
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
        },

    },
    data() {
        return {
            categoryList: [],
            showEditForm: '',
            editForm: {
                name: '',
            },
            categoryIds: [],
        }
    },
    computed: {
        setCategories() {
            return this.categoryList = this.categories;
        }
    },
    methods: {
        deleteAllCategory() {
            if (confirm('Are you sure to remove category?')){
                this.sending = true;
                axios.post(this.route('category.delete.all', {
                    ids: this.categoryIds
                }))
                    .then(response => {
                        if(response.status === 200){
                            this.sending = false;
                            this.categoryIds = [];
                            this.$emit('loadCategory');
                        }
                    })
                    .catch(error => {
                        this.sending = false;
                        console.log(error.message);
                    })
            }
        },

        deleteCategory(category_id){

            if (confirm('Are you sure to remove category?')){
                this.sending = true;
                axios.delete(this.route('category.delete', category_id))
                .then(response => {
                    if(response.status === 200){
                        this.sending = false;
                        this.$emit('loadCategory');
                    }
                })
                .catch(error => {
                    this.sending = false;
                    console.log(error.message);
                })
            }
        },
        editCategory(category_id){
            this.sending = true;
            axios.patch(this.route('category.update', category_id), {
                name: this.editForm.name
            })
                .then(response => {
                    if(response.status === 204){
                        this.sending = false;
                        this.showEditForm = '';
                        this.$emit('loadCategory');
                    }
                })
                .catch(error => {
                    this.sending = false;
                    console.log(error.message);
                })
        },
        setCategoryId(category_id){
            this.categoryIds.push(category_id);
        }
    },
    created() {

    }
}
</script>

<style scoped>

</style>
