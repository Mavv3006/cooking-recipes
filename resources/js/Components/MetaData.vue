<script setup>
import dayjs from 'dayjs';

const props = defineProps({
    description: String,
    title: String,
    is_favorite: Boolean, // TODO: refactor using camelCase
    ratings: Object,
    creation_date: String,
    difficulty: String
})
const emit = defineEmits(['toggleFavorite'])

</script>

<template>
    <div>{{ title }}</div>

    <button @click="$emit('toggleFavorite')">
        <i v-if="is_favorite" class="fa-solid fa-heart"></i>
        <i v-else class="fa-regular fa-heart"></i>
    </button>

    <div>Bewertung: {{ ratings.avg }} ({{ ratings.count }})</div>

    <div>{{ description }}</div>

    <div>Erstellt: {{ dayjs(creation_date).format('DD.MM.YYYY HH:mm') }}</div>

    <div>Schwierigkeit: {{ difficulty }}</div>
</template>
