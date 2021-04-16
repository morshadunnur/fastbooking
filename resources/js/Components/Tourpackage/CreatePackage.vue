<template>
    <div>
        <h5 class="text-gray-900 font-bold leading-tight mb-4 p-2">Create Package</h5>
        <form class="w-full max-w-lg">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="select_area">
                        Select Area
                    </label>
                    <select class="fastbooking-input" v-model="createForm.category_id" id="select_area">
                        <option :value="category.id" v-for="(category, index) in categories" :key="index">
                            {{ category.name }}
                        </option>
                    </select>

                </div>
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="grid-first-name">
                        Package Name
                    </label>
                    <input class="fastbooking-input" v-model="createForm.package_title" id="grid-first-name" type="text"
                           placeholder="Package Title">

                </div>
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="place_name">
                        Place Name
                    </label>
                    <input class="fastbooking-input" v-model="createForm.place_name" id="place_name" type="text"
                           placeholder="Place Name">

                </div>
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="package_duration">
                        Package Duration
                    </label>
                    <input class="fastbooking-input" v-model="createForm.duration" id="package_duration" type="text"
                           placeholder="Example: 1 Day, 2 Days..">

                </div>
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="package_description">
                        Package Description
                    </label>
                    <textarea class="fastbooking-input" v-model="createForm.description" id="package_description"
                              rows="10" cols="5" placeholder="Example: 1 Day, 2 Days..">
                    </textarea>

                </div>
            </div>
            <div class="flex flex-wrap mb-6">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    @click.prevent="createNewTourPackage">Save
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "CreatePackage",
    data() {
        return {
            sending: false,
            categories: [],
            createForm: {
                package_title: '',
                place_name: '',
                duration: '',
                description: '',
                category_id: '',
            }
        }
    },
    methods: {
        getCategories() {
            axios.get(this.route('category.list.data'))
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data);
                        this.categories = response.data;
                    }
                })
                .catch(error => {
                    console.log(error.message);
                })
        },
        createNewTourPackage() {
            this.sending = true;
            axios.post(this.route('tour.package.store'), this.createForm)
                .then(response => {
                    if (response.status === 200) {
                        this.sending = false;
                        this.setDefaultCreateForm();
                    }
                })
                .catch(error => {
                    this.sending = false;
                })
        },
        setDefaultCreateForm() {
            this.createForm = {
                package_title: '',
                place_name: '',
                duration: '',
                description: '',
                category_id: '',
            }
        }
    },
    created() {
        this.getCategories();
    }
}
</script>

<style scoped>

</style>
