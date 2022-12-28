<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import MetaData from "@/Components/MetaData.vue";
import IngredientsSection from "@/Components/IngredientsSection.vue";
import StepSection from "@/Components/StepSection.vue";
import Author from "@/Components/Author.vue";
import CommentSection from "@/Components/CommentSection.vue";
import {computed} from "vue";

const props = defineProps({
    recipe: Object,
    ingredients: Array,
    steps: Array,
    user: Object,
    isFavorite: Boolean,
    isLoggedIn: Boolean,
    comments: Array,
    ratings: Object,
    times: Object,
    images: Array
});

console.debug(props.images);

const isAuthor = computed(() => props.recipe.user_id === usePage().props.value.auth?.user?.id);

const scrollToComments = () => {
    document.getElementById('comments').scrollIntoView({behavior: "smooth", block: "start"});
}

const deleteRecipe = () => {
    useForm({_method: 'DELETE'}).delete(route('recipes.destroy', {'recipe': props.recipe.id}));
}
</script>

<template>
    <AppLayout title="Detailsansicht">
        <MetaData
            :comments_count="comments.length"
            :creation_date="recipe.created_at"
            :description="recipe.description"
            :difficulty="recipe.difficulty"
            :is-favorite="isFavorite"
            :ratings="ratings"
            :title="recipe.title"
            @scroll-to-comments="scrollToComments"
        />

        <hr class="my-4">

        <!--        <ImageViewer v-if="images !== undefined" :images="images"/>-->

        <!-- Meta data-->
        <!--        <section-->
        <!--            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 my-3">-->

        <!--            <button-->
        <!--                v-if="isAuthor"-->
        <!--                class="block items-center px-3 mb-3 py-1 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"-->
        <!--                @click="deleteRecipe">-->
        <!--                Rezept löschen-->
        <!--            </button>-->

        <!--            <Link v-if="isAuthor"-->
        <!--                  :href="route('recipes.edit', {'recipe': recipe.id})"-->
        <!--                  class="block items-center px-3 mb-3 py-1 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"-->
        <!--            >-->
        <!--                Rezept bearbeiten-->
        <!--            </Link>-->
        <!--            <Link v-if="usePage().props.value.auth?.user?.id !== null"-->
        <!--                  :href="route('image.create', {'recipe': recipe.id})"-->
        <!--                  class="block items-center px-3 mb-3 py-1 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"-->
        <!--            >-->
        <!--                Bild hochladen-->
        <!--            </Link>-->

        <!--            <RatingForm :recipe_id="recipe.id"/>-->
        <!--        </section>-->

        <hr class="my-4">

        <IngredientsSection :ingredients="ingredients"/>

        <hr class="my-4">

        <StepSection :is-favorite="isFavorite" :steps="steps" :times="times"/>

        <hr class="my-4">

        <Author :username="user.name"/>

        <hr class="my-4">

        <CommentSection :comments="comments"/>

    </AppLayout>
</template>
