<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    recipe: Object,
    ingredients: Array,
    steps: Array,
    user: Object
});
</script>


<template>
    <AppLayout title="Detailsansicht">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ recipe.title }}
            </h2>
        </template>

        <section
            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-12">
            <p>{{ recipe.description }}</p>
            <p>Erstellt {{ recipe.created_at }} von {{ user.name }}</p>
        </section>

        <section
            class="max-w-7xl mx-auto mt-6 bg-white overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 sm:px-6 lg:px-8">
            <h3>Zutaten</h3>

            <ul>
                <li v-for="ingredient in ingredients">
                    {{ ingredient.quantity }} {{ ingredient.uom }} {{ ingredient.name }}
                </li>
            </ul>
        </section>

        <section
            class="max-w-7xl mx-auto mt-6 bg-white overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 sm:px-6 lg:px-8">
            <h3>Schritte</h3>

            <ul class="flex flex-col space-y-4">
                <li v-for="step in steps">
                    {{ step.description }}
                </li>
            </ul>
        </section>

    </AppLayout>
</template>
