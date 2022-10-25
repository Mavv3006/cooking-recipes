<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Textarea from "@/Components/Textarea.vue";

const form = useForm({
    _method: 'POST',
    title: '',
    description: '',
    steps: [{description: '', order: 1}],
    ingredients: [{description: ''}],
    difficulty: '',
});

const submitForm = () => {
    // console.debug({
    //     title: form.title,
    //     description: form.description,
    //     steps: form.steps,
    //     ingredients: form.ingredients,
    //     difficulty: form.difficulty
    // });
    form.post(route('recipes.store'), {onSuccess: () => form.reset()});
}

const addStep = (event) => {
    if (event) event.preventDefault();
    form.steps.push({description: '', order: form.steps[form.steps.length - 1].order + 1});
};

const removeStep = (index, event) => {
    if (event) event.preventDefault();
    form.steps.splice(index, 1);
};


const addIngredient = (event) => {
    if (event) event.preventDefault();
    form.ingredients.push({description: ''});
}

const removeIngredient = (index, event) => {
    if (event) event.preventDefault();
    form.ingredients.splice(index, 1);
}
</script>

<template>
    <AppLayout title="Erstelle Rezept">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Erstelle ein neues Rezept
            </h2>
        </template>

        <form @submit.prevent="submitForm" class="py-12">
            <section
                class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4">
                Metadaten über das Rezept
                <div class="flex flex-col mt-4 space-y-4">
                    <div>
                        <!-- Titel -->
                        <InputLabel for="title">Titel des Rezepts</InputLabel>
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="w-full"
                            placeholder="z.B.: Gefüllte Tomaten"
                        />
                        <InputError :message="form.errors.title"/>
                    </div>
                    <div>
                        <!-- Beschreibung -->
                        <InputLabel for="description">Beschreibung des Rezepts</InputLabel>
                        <TextInput
                            id="description"
                            v-model="form.description"
                            type="text"
                            class="w-full"
                            placeholder="z.B.: ein leckeres Abendessen."
                        />
                        <InputError :message="form.errors.description"/>
                    </div>
                </div>

                <p class="text-sm mt-4 mb-2">
                    Schwierigkeit
                </p>
                <div class="flex space-x-4">
                    <div class="flex space-x-2">
                        <input
                            type="radio"
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="difficulty"
                            id="easy"
                            value="easy"
                            v-model="form.difficulty"
                        >
                        <InputLabel for="easy">Einfach</InputLabel>
                    </div>
                    <div class="flex space-x-2">
                        <input
                            type="radio"
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="difficulty"
                            id="normal"
                            value="normal"
                            v-model="form.difficulty"
                        >
                        <InputLabel for="normal">Mittel</InputLabel>
                    </div>
                    <div class="flex space-x-2">
                        <input
                            type="radio"
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="difficulty"
                            id="hard"
                            value="hard"
                            v-model="form.difficulty"
                        >
                        <InputLabel for="hard">Schwer</InputLabel>
                    </div>
                </div>
                <InputError :message="form.errors.difficulty"/>

                <!-- Tags (Zukunft) -->
            </section>

            <div class="flex max-w-7xl mx-auto space-x-8">
                <section
                    class="mt-6 bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4 basis-1/3">
                    <!-- Zutaten -->
                    Zutaten
                    <p class="text-sm text-gray-600">
                        Bitte in folgendem Format eingeben:
                        <br>
                        Menge&#x2423;Einheit&#x2423;Zutat
                    </p>
                    <InputError :message="form.errors.ingredients"/>
                    <div class="block">
                        <div class="flex flex-col space-y-4 mt-4 empty:mt-0" id="recipe-step-list">
                            <div v-for="(ingredient, index) in form.ingredients" class="flex space-x-2">
                                <TextInput type="text" class="w-full" v-model="ingredient.description"
                                           placeholder="z.B.: 180 g Zucker"/>
                                <button @click="removeIngredient(index, $event)"
                                        class="bg-red-500 hover:bg-red-600 focus:bg-red-700 text-white px-4 rounded-lg">
                                    Löschen
                                </button>
                            </div>
                        </div>
                        <button
                            class="mt-4 w-full bg-indigo-200 h-12 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"
                            @click="addIngredient($event)"
                        >
                            Weitere Zutat hinzufügen
                        </button>
                    </div>

                </section>

                <section
                    class="mt-6 bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4 basis-2/3">
                    <!-- Schritte -->
                    Schritte
                    <InputError :message="form.errors.steps"/>
                    <div class="block">
                        <div class="flex flex-col space-y-4 mt-4 empty:mt-0" id="recipe-step-list">
                            <div v-for="(step, index) in form.steps" class="flex space-x-2">
                                <Textarea class="w-full" v-model="step.description"/>
                                <button @click="removeStep(index, $event)"
                                        class="bg-red-500 hover:bg-red-600 focus:bg-red-700 text-white p-4 rounded-lg">
                                    Löschen
                                </button>
                            </div>
                        </div>
                        <button
                            class="mt-4 w-full bg-indigo-200 h-12 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"
                            @click="addStep($event)"
                        >
                            Weiteren Schritt hinzufügen
                        </button>
                    </div>
                </section>
            </div>

            <section>
                <!--Bilder -->
            </section>

            <div
                class="mt-6 bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4">
                <!--                <ActionMessage :on="form.recentlySuccessful">-->
                <!--                    Rezept gespeichert.-->
                <!--                </ActionMessage>-->
                <PrimaryButton :disables="form.processing">
                    Speichern
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
