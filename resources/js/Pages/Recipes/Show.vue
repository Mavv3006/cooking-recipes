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
    ratings: Object,
    times: Object
});

const toggleFavorite = () => {
    useForm({_method: 'POST'}).post(route('favorites.store', {'recipe': props.recipe.id}));
}
</script>

<template>
    <AppLayout title="Detailsansicht">
        <MetaData
            :is_favorite="is_favorite"
            :description="recipe.description"
            :difficulty="recipe.difficulty"
            :ratings="ratings"
            :title="recipe.title"
            :creation_date="recipe.created_at"
            @toggle-favorite="toggleFavorite"/>

        <hr>

        <!-- Meta data-->
        <section
            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-12">
            
            <div class="mt-2">
                <RatingForm :recipe_id="recipe.id"/>
            </div>
            
            <div class="mt-2">
                Zeiten:
                <div class="flex space-x-4">
                    <span v-for="time in times" class="bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200">
                       {{ time.time.name }}: {{
                            time.duration
                        }} {{ time.duration > 1 ? time.times_unit.long + 'n' : time.times_unit.long }}
                    </span>
                </div>
            </div>
        </section>


        <IngredientsSection :ingredients="ingredients"/>

        <hr>

        <StepSection :steps="steps"/>

        <hr>

        <Author :username="user.name"/>

        <hr>

        <CommentSection :comments="comments"/>

    </AppLayout>
</template>
