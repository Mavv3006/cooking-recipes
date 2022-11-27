<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import MetaData from "@/Components/MetaData.vue";
import IngredientsSection from "@/Components/IngredientsSection.vue";
import StepSection from "@/Components/StepSection.vue";
import Author from "@/Components/Author.vue";
import CommentSection from "@/Components/CommentSection.vue";

const props = defineProps({
    recipe: Object,
    ingredients: Array,
    steps: Array,
    user: Object,
    is_favorite: Boolean, // TODO: refactor using camelCase
    test: Object,
    is_logged_in: Boolean, // TODO: refactor using camelCase
    comments: Array,
    ratings: Object
});

const toggleFavorite = () => {
    useForm({_method: 'POST'}).post(route('favorites.store', {'recipe': props.recipe.id}));
}
</script>


<template>
    <AppLayout title="Detailsansicht">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ recipe.title }}
            </h2>
        </template>

        <MetaData
            :is_favorite="is_favorite"
            :description="recipe.description"
            :difficulty="recipe.difficulty"
            :ratings="ratings"
            :title="recipe.title"
            :creation_date="recipe.created_at"
            @toggle-favorite="toggleFavorite"/>

        <hr>

        <IngredientsSection :ingredients="ingredients"/>

        <hr>

        <StepSection :steps="steps"/>

        <hr>

        <Author :username="user.name"/>

        <hr>

        <CommentSection :comments="comments"/>

    </AppLayout>
</template>
