<template>
    <form class="w-full max-w-lg">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                    Category Name
                </label>
                <input class="shadow appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" v-model="category.name" placeholder="Jane">
            </div>
        </div>
        <div class="flex flex-wrap mb-6">
            <button @click.prevent="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
        </div>
    </form>
</template>

<script>
export default {
    name: "CreateCategory",
    data() {
        return{
            sending: false,
            category: {
                name: ''
            }
        }
    },
    methods: {
        submit() {
            const data = {
                name: this.category.name,
            }
            this.$inertia.post(this.route('category.store'), data, {
                onStart: () => this.sending = true,
                onFinish: () => {
                    this.sending = false;
                    this.category.name = '';
                },
            });

        }
    }
}
</script>

<style scoped>

</style>
