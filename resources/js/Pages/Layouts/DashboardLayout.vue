<template>
    <div class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">
        <slot name="header-nav"></slot>
        <div class="flex flex-col md:flex-row">
            <slot name="sidebar"></slot>
            <slot name="main-content"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "DashboardLayout",
}
</script>

<style scoped>

</style>
