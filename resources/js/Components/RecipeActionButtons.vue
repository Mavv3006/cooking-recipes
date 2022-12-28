<script setup>
import {useForm, usePage} from "@inertiajs/inertia-vue3";

if (usePage().props.value.recipe.id === null) {
    console.error("recipe id of the shown recipe could not be extracted.");
}

const props = defineProps({
    isFavorite: Boolean
})

const toggleFavorite = () => {
    useForm({_method: 'POST'}).post(route('favorites.store', {'recipe': usePage().props.value.recipe.id}));
}
</script>

<template>
    <div class="flex justify-evenly mt-4">
        <button
            class="border-green-900 border-solid border rounded-lg px-4 py-2 hover:bg-green-900/10 active:bg-green-900/20 focus:bg-green-900/20 focus:outline-none focus:ring transition flex items-center flex-col text-sm">
            <i class="fa-solid fa-print"></i>
            Drucken / PDF
        </button>

        <button
            class="border-green-900 border-solid border rounded-lg px-4 py-2 hover:bg-green-900/10 active:bg-green-900/20 focus:bg-green-900/20 focus:outline-none focus:ring transition flex items-center flex-col text-sm">
            <i class="fa-solid fa-share"></i>
            Teilen
        </button>

        <button
            class="border-green-900 border-solid border rounded-lg px-4 py-2 hover:bg-green-900/10 active:bg-green-900/20 focus:bg-green-900/20 focus:outline-none focus:ring transition flex items-center flex-col text-sm"
            @click="toggleFavorite">
            <i v-if="isFavorite" class="fa-solid fa-heart"></i>
            <i v-else class="fa-regular fa-heart"></i>
            Favorisieren
        </button>
    </div>
</template>
