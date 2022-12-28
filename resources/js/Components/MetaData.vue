<script setup>
import dayjs from 'dayjs';
import {computed} from "vue";
import RecipeActionButtons from "@/Components/RecipeActionButtons.vue";

const props = defineProps({
    description: String,
    title: String,
    isFavorite: Boolean,
    ratings: Object,
    creation_date: String,
    difficulty: String,
    comments_count: Number
})

const emit = defineEmits(['scrollToComments'])

const ratingPercentage = computed(() => (props.ratings.average / props.ratings.count).toFixed(3) * 100)

const formatAverage = (average) => new Intl.NumberFormat('de-DE', {minimumFractionDigits: 2}).format(average);

const formatCreationDate = (date) => dayjs(date).format('DD.MM.YYYY');

</script>

<template>
    <div class="text-2xl mt-4">{{ title }}</div>

    <RecipeActionButtons :is-favorite="isFavorite"/>

    <div class="mt-4">{{ description }}</div>

    <div class="flex items-center space-x-2 mt-4">
        <div class="relative w-[90px] h-4">
            <div :style="{ width: ratingPercentage + '%' }"
                 class="absolute text-amber-500 overflow-hidden z-[1] top-0 left-0">
                <div class="flex">
                    <i v-for="n in 5" class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="absolute text-gray-300 z-[0]">
                <div class="flex">
                    <i v-for="n in 5" class="fa-solid fa-star"></i>
                </div>
            </div>
        </div>
        <div>
            {{ formatAverage(ratings.average) }} ({{ ratings.count }} Bewertungen)
        </div>
    </div>

    <div class="mt-4 hover:cursor-pointer w-fit hover:underline" @click="$emit('scrollToComments')">
        {{ comments_count }} Kommentare
    </div>

    <div class="mt-4 flex space-x-4 text-sm">
        <div class="bg-green-900/20 hover:bg-green-900/10 transition px-4 rounded-full py-1">{{ difficulty }}</div>
        <div class="bg-green-900/20 hover:bg-green-900/10 transition px-4 rounded-full py-1">
            <i class="fa-solid fa-calendar-days mr-1"></i>
            {{ formatCreationDate(creation_date) }}
        </div>
    </div>
</template>
