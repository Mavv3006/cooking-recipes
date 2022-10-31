<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from '@inertiajs/inertia-vue3';

const props = defineProps({
    favorites: Array
})
</script>

<template>
    <AppLayout title="Favoriten">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Favorisierte Rezepte
            </h2>
        </template>

        <section
            class="bg-white max-w-7xl mx-auto rounded-lg mt-12 shadow-md py-4 sm:px-3 lg:px-4 overflow-hidden grid md:grid-cols-2 xl:grid-cols-3 gap-2">
            <Link v-for="recipe in favorites" :href="route('recipes.show', {'recipe': recipe.id})"
                  class="hover:bg-gray-100 hover:shadow-lg hover:cursor-pointer rounded-lg sm:px-3 lg:px-4 py-3">
                <p>{{ recipe.title }} <span class="text-sm text-gray-600">von {{ recipe.user.name }}</span></p>
                <p class="truncate">{{ recipe.description }}</p>
                <p class="text-sm text-gray-600">Schwierigkeit: {{ recipe.difficulty }}</p>
            </Link>
        </section>
    </AppLayout>
</template>
