<script setup>
import dayjs from 'dayjs';
import {computed} from "vue";

const props = defineProps({
    description: String,
    title: String,
    is_favorite: Boolean, // TODO: refactor using camelCase
    ratings: Object,
    creation_date: String,
    difficulty: String
})
const emit = defineEmits(['toggleFavorite'])

const ratingPercentage = computed(() => {
    return (props.ratings.avg / props.ratings.count).toFixed(3) * 100;
})

</script>

<template>
    <div>{{ title }}</div>

    <button @click="$emit('toggleFavorite')">
        <i v-if="is_favorite" class="fa-solid fa-heart"></i>
        <i v-else class="fa-regular fa-heart"></i>
    </button>

    <div class="flex items-center space-x-2">
        <div class="relative w-[90px] h-4">
            <div class="absolute text-[#e7711b] overflow-hidden z-[1] top-0 left-0"
                 :style="{ width: ratingPercentage + '%' }">
                <div class="flex">
                    <i class="fa-solid fa-star" v-for="n in 5"></i>
                </div>
            </div>
            <div class="absolute text-[#c5c5c5] z-[0]">
                <div class="flex">
                    <i class="fa-solid fa-star" v-for="n in 5"></i>
                </div>
            </div>
        </div>
        <div>({{ ratings.count }})</div>
    </div>

    <div>{{ description }}</div>

    <div>Erstellt: {{ dayjs(creation_date).format('DD.MM.YYYY HH:mm') }}</div>

    <div>Schwierigkeit: {{ difficulty }}</div>
</template>
