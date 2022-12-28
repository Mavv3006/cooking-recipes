<script setup>

import {ref} from "vue";

const props = defineProps({
    images: Array,
});

const currentIndex = ref(0);

const prevSlide = () => currentIndex.value = currentIndex.value === 0 ? props.images.length - 1 : currentIndex.value - 1;

const nextSlide = () => currentIndex.value = currentIndex.value === props.images.length - 1 ? 0 : currentIndex.value + 1;

</script>

<template>
    <div class="w-full md:w-1/2 xl:w-1/3 aspect-[4/3] w-full m-auto pt-4 relative group">
        <div :style="{ 'backgroundImage': `url(../storage/${images[currentIndex].path})` }"
             class="w-full h-full rounded-2xl bg-center bg-cover duration-500"></div>

        <div
            class="text-sm px-2 w-fit rounded-sm -translate-x-0 translate-y-[-50%] bg-white shadow-md left-5 top-10 absolute">
            {{ currentIndex }} / {{ images.length }} {{ images[currentIndex].user.name }}
        </div>

        <div
            class="hidden group-hover:block absolute top-[50%] -translate-x-0 translate-y-[-50%] left-5 rounded-full p-2 w-[40px] h-[40px] text-center cursor-pointer bg-white hover:shadow-2xl focus:ring focus:outline-none">
            <i class="fa-solid fa-chevron-left" @click="prevSlide"></i>
        </div>

        <div
            class="hidden group-hover:block absolute top-[50%] -translate-x-0 translate-y-[-50%] right-5 rounded-full p-2 w-[40px] h-[40px] text-center cursor-pointer bg-white hover:shadow-2xl focus:ring focus:outline-none">
            <i class="fa-solid fa-chevron-right" @click="nextSlide"></i>
        </div>
    </div>

</template>
