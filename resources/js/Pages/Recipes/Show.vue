<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import Comment from "@/Components/Comment.vue";
import {ref} from "vue";

dayjs.extend(relativeTime);

const props = defineProps({
    recipe: Object,
    ingredients: Array,
    steps: Array,
    user: Object,
    is_favorite: Boolean,
    test: Object,
    is_logged_in: Boolean,
    comments: Array
});

const favoriteForm = useForm({
    _method: 'POST'
});
const commentCreateForm = useForm({
    _method: 'POST',
    comment: '',
    recipe_id: props.recipe.id
});

const commenting = ref(false);

const toggleFavorite = () => {
    favoriteForm.post(route('favorites.store', {'recipe': props.recipe.id}));
}

const submitCreateForm = () => {
    console.log('submitting the create form', commentCreateForm);
    commentCreateForm.post(route('comment.create'), {onSuccess: () => commenting.value = false});
    commentCreateForm.reset();
};
</script>


<template>
    <AppLayout title="Detailsansicht">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ recipe.title }}
            </h2>
        </template>

        <section
            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-12"
            v-if="is_logged_in">
            <div>Ist favorisiert? {{ is_favorite ? "Ja" : "Nein" }}</div>
            <div class="mt-4">
                <button @click="toggleFavorite"
                        class=" inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    {{ is_favorite ? "Favorit entfernen" : "Favorit hinzufügen" }}
                </button>
            </div>
        </section>

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

        <section
            class="max-w-7xl mx-auto mt-6 bg-white overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 sm:px-6 lg:px-8">
            <h3>Kommentare</h3>

            <div class="flex justify-center">
                <button
                    class="border-2 rounded-full py-2 px-4 hover:bg-gray-200 hover:border-gray-300 active:bg-gray-300 active:border-gray-400 hover:shadow-sm"
                    @click="commenting=true"
                    v-if="commenting===false">
                    <span class="fa-solid fa-pen pr-2"></span>
                    Kommentar schreiben
                </button>
            </div>
            <form v-if="commenting===true" @submit.prevent="submitCreateForm">
                    <textarea v-model="commentCreateForm.comment"
                              class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                <InputError :message="commentCreateForm.errors.comment" class="mt-2"/>
                <div class="space-x-2">
                    <PrimaryButton class="mt-4">Save</PrimaryButton>
                    <button class="mt-4"
                            @click="commenting = false; commentCreateForm.reset(); commentCreateForm.clearErrors()">
                        Cancel
                    </button>
                </div>
            </form>

            <div class="flex space-y-6 flex-col mt-4">
                <Comment v-for="comment in comments" :comment="comment" :key="comment.id"></Comment>
            </div>
        </section>

    </AppLayout>
</template>
