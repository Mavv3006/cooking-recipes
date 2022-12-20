<script setup>

const props = defineProps({
    images: Array
});

console.debug('image props: ', props.images);

</script>

<template>
    <div class="flex flex-row flex-wrap space-x-8">
        <div v-for="image in images" class="w-1/6">
            <img :src="`../storage/${image.path}`" alt="one of the images for a recipe">
        </div>
    </div>
</template>
