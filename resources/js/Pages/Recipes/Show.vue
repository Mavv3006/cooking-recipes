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
    times: Object
});

const isAuthor = computed(() => props.recipe.user_id === usePage().props.value.auth?.user?.id);

const deleteRecipe = () => {
    useForm({_method: 'DELETE'}).delete(route('recipes.destroy', {'recipe': props.recipe.id}));
}

const scrollToComments = () => {
    console.debug("scrolling to comments clicked");
    document.getElementById('comments').scrollIntoView({behavior: "smooth", block: "start"});
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

        <!--            <RatingForm :recipe_id="recipe.id"/>-->

        <!--            <div v-if="times.length>0" class="mt-2">-->
        <!--                Zeiten:-->
        <!--                <div class="flex space-x-4">-->
        <!--                    <span v-for="time in times" class="bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200">-->
        <!--                       {{ time.time.name }}: {{-->
        <!--                            time.duration-->
        <!--                        }} {{ time.duration > 1 ? time.times_unit.long + 'n' : time.times_unit.long }}-->
        <!--                    </span>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </section>-->
        <!---->
        <!--        <hr>-->

        <IngredientsSection :ingredients="ingredients"/>

        <hr class="my-4">

        <StepSection :is-favorite="isFavorite" :steps="steps" :times="times"/>

        <hr class="my-4">

        <Author :username="user.name"/>

        <hr class="my-4">

        <div id="comments">
            <CommentSection :comments="comments"/>
        </div>

    </AppLayout>
</template>
