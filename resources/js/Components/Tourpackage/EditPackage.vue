<template>
    <div>
        <form class="w-full">
            <div class="flex flex-row justify-center -mx-3 mb-6">
                <div class="flex flex-col md:w-1/2">
                    <div class="w-full px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                               for="select_area">
                            Select Area
                        </label>
                        <select class="fastbooking-input" v-model="editPackageData.category_id" id="select_area">
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
                        <input class="fastbooking-input" v-model="editPackageData.title" id="grid-first-name"
                               type="text"
                               placeholder="Package Title">

                    </div>
                    <div class="w-full px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                               for="place_name">
                            Place Name
                        </label>
                        <input class="fastbooking-input" v-model="editPackageData.place_name" id="place_name"
                               type="text"
                               placeholder="Place Name">

                    </div>
                    <div class="w-1/2 px-3 mb-6 md:mb-0">
                        <div>
                            <img :src="editPackageData.feature_image" alt="" class="w-12">
                        </div>
                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="feature_image">
                                Feature Image
                            </label>
                            <input class="fastbooking-input" id="feature_image" @change="setUpdateImage"
                                   type="file">

                        </div>

                    </div>

                    <div class="w-1/2 px-3 mb-6 md:mb-0">

                        <div class="w-full px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="feature_image">
                                Gallery Image
                            </label>
                            <input class="fastbooking-input" id="gallery_image" @change="setGalleryImages"
                                   type="file" accept="image/*" multiple>

                        </div>

                        <div class="w-full px-3 mb-6 md:mb-0" v-for="(galleryImage, imageIndex) in editPackageData.gallery" :key="imageIndex">
                            <div style="z-index: 999; position: absolute">
                                <DeleteIcon @click="deleteGalleryImage(galleryImage.original)" style="cursor: pointer"/>
                            </div>
                            <div>
                                <img :src="galleryImage.original" alt="" >
                            </div>
                        </div>

                    </div>


                </div>
                <div class="flex flex-col md:w-1/2">
                    <div class="w-full px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                               for="package_duration">
                            Package Duration
                        </label>
                        <input class="fastbooking-input" v-model="editPackageData.duration" id="package_duration"
                               type="text"
                               placeholder="Example: 1 Day, 2 Days..">

                    </div>
                    <div class="w-full px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                               for="package_description">
                            Package Description
                        </label>
                        <textarea class="fastbooking-input" v-model="editPackageData.descriptions"
                                  id="package_description"
                                  rows="10" cols="5" placeholder="Example: 1 Day, 2 Days..">
                    </textarea>

                    </div>
                </div>

            </div>

            <div class="flex flex-wrap mb-6">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    @click.prevent="updateTourPackage">Update
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import {DeleteIcon} from 'vue-feather-icons';
export default {
    name: "EditPackage",
    data() {
        return {
            categories: [],
            sending: false,
            editFeatureImage: '',
            editGalleryImage: [],
        }
    },
    components: {
      DeleteIcon
    },
    props: {
        editPackageData: {
            type: Object,
            required: true,
            default: {},
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
        setUpdateImage(event){
            this.editFeatureImage = event.target.files[0];
        },
        setGalleryImages(event){
            for (let value of event.target.files){
                this.editGalleryImage.push(value);
            }
        },
        updateTourPackage() {
            let formData = new FormData();
            formData.append('package_id', this.editPackageData.id);
            formData.append('category_id', this.editPackageData.category_id);
            formData.append('package_title', this.editPackageData.title);
            formData.append('place_name', this.editPackageData.place_name);
            formData.append('duration', this.editPackageData.duration);
            formData.append('descriptions', this.editPackageData.descriptions);
            formData.append('feature_image', this.editFeatureImage);
            for(let gallery of this.editGalleryImage){
                formData.append('gallery_images[]', gallery);
            }
            this.editFeatureImage++;
            this.editGalleryImage++;
            this.sending = true;
            axios.post(this.route('tour.package.update'), formData)
                .then(response => {
                    if (response.status === 204) {
                        this.sending = false;
                        this.editFeatureImage = 0;
                        this.editGalleryImage = 0;
                        this.$emit('loadPackages');
                    }
                })
                .catch(error => {
                    this.sending = false;
                    this.editFeatureImage = 0;
                    this.editGalleryImage = 0;
                })

        },

        deleteGalleryImage(image){
            axios.post(this.route('tour.package.image.remove'), {
                package_id: this.editPackageData.id,
                image: image
            })
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data);
                        this.editPackageData.gallery = response.data.gallery;
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
