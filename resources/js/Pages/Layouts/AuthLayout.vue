<template>
    <div class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
        <div class="flex flex-col md:flex-row">
            <slot name="main-content"></slot>
        </div>
    </div>
</template>

<script>
export default {
name: "AuthLayout"
}
</script>

<style scoped>

</style>
