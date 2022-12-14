<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Textarea from "@/Components/Textarea.vue";
import NumberInput from "@/Components/NumberInput.vue";

const props = defineProps({
    recipe: Object,
    ingredients: Array,
    steps: Array,
    user: Object,
    comments: Array,
    ratings: Object,
    times: Array,
    timeUnitOfMeasures: Array
});

const form = useForm({
    _method: 'PUT',
    title: props.recipe.title,
    description: props.recipe.description,
    steps: props.steps,
    ingredients: props.ingredients.map((value) => {
        return {
            description: `${value['quantity']} ${value['uom']} ${value['name']}`
        };
    }),
    difficulty: props.recipe.difficulty,
    times: props.times.map((value) => {
        return {id: value.times_id, uom_id: value.times_unit_id, duration: value.duration};
    })
});

const submitForm = () => {
    console.log(form.data());
    form.put(route('recipes.update', {'recipe': props.recipe.id}));
}

const addStep = (event) => {
    if (event) event.preventDefault();
    form.steps.push({description: ''});
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
    <AppLayout :title="`${ recipe.title } bearbeiten`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bearbeite das Rezept für "{{ recipe.title }}"
            </h2>
        </template>

        <form class="py-12" @submit.prevent="submitForm">

            <section
                class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4">
                <div class="flex space-x-16">
                    <div class="flex flex-col  space-y-4 flex-1">
                        Metadaten über das Rezept
                        <div class="mt-4">
                            <!-- Titel -->
                            <InputLabel for="title">Titel des Rezepts</InputLabel>
                            <TextInput
                                id="title"
                                v-model="form.title"
                                class="w-full"
                                placeholder="z.B.: Gefüllte Tomaten"
                                type="text"
                            />
                            <InputError :message="form.errors.title"/>
                        </div>
                        <div>
                            <!-- Beschreibung -->
                            <InputLabel for="description">Beschreibung des Rezepts</InputLabel>
                            <TextInput
                                id="description"
                                v-model="form.description"
                                class="w-full"
                                placeholder="z.B.: ein leckeres Abendessen."
                                type="text"
                            />
                            <InputError :message="form.errors.description"/>
                        </div>

                        <div>
                            <p class="text-sm mt-4 mb-2">
                                Schwierigkeit
                            </p>
                            <div class="flex space-x-4">
                                <div class="flex space-x-2">
                                    <input
                                        id="easy"
                                        v-model="form.difficulty"
                                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="difficulty"
                                        type="radio"
                                        value="easy"
                                    >
                                    <InputLabel for="easy">Einfach</InputLabel>
                                </div>
                                <div class="flex space-x-2">
                                    <input
                                        id="normal"
                                        v-model="form.difficulty"
                                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="difficulty"
                                        type="radio"
                                        value="normal"
                                    >
                                    <InputLabel for="normal">Mittel</InputLabel>
                                </div>
                                <div class="flex space-x-2">
                                    <input
                                        id="hard"
                                        v-model="form.difficulty"
                                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="difficulty"
                                        type="radio"
                                        value="hard"
                                    >
                                    <InputLabel for="hard">Schwer</InputLabel>
                                </div>
                            </div>
                            <InputError :message="form.errors.difficulty"/>
                        </div>
                    </div>

                    <div class="flex-1">
                        Zeiten
                        <div class="flex flex-col mt-4 space-y-2">
                            <div v-for="time in times">
                                {{ time.time.name }}
                                <div class="flex space-x-4">
                                    <template
                                        v-for="scope in [{form_time: form.times.find((value) => value.id === time.time.id)}]">
                                        <div>
                                            <InputLabel
                                                :for="`time-duration-${scope.form_time.id }`">
                                                Dauer
                                            </InputLabel>
                                            <NumberInput
                                                :id="`time-duration-${scope.form_time.id }`"
                                                v-model.number="scope.form_time.duration"
                                                min="0"
                                                step="any"
                                            ></NumberInput>
                                        </div>
                                        <div>
                                            <InputLabel
                                                :for="`time-uom-${scope.form_time.id}`">
                                                Einheit
                                            </InputLabel>
                                            <select
                                                :id="`time-uom-${scope.form_time.id}`"
                                                v-model="scope.form_time.uom_id"
                                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                required>
                                                <option
                                                    v-for="timeUnitOfMeasure in timeUnitOfMeasures"
                                                    :value="timeUnitOfMeasure.id"
                                                >{{ timeUnitOfMeasure.long }}(n)
                                                </option>
                                            </select>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tags (Zukunft) -->
            </section>

            <div class="flex max-w-7xl mx-auto space-x-8">
                <section
                    class="mt-6 bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 basis-1/3">
                    <!-- Zutaten -->
                    Zutaten
                    <p class="text-sm text-gray-600">
                        Bitte in folgendem Format eingeben:
                        <br>
                        Menge&#x2423;Einheit&#x2423;Zutat
                    </p>
                    <InputError :message="form.errors.ingredients"/>
                    <div class="block">
                        <div id="recipe-step-list" class="flex flex-col space-y-4 mt-4 empty:mt-0">
                            <div v-for="(ingredient, index) in form.ingredients" class="flex space-x-2">
                                <TextInput v-model="ingredient.description" class="w-full"
                                           placeholder="z.B.: 180 g Zucker"
                                           type="text"/>
                                <button class="bg-red-500 hover:bg-red-600 focus:bg-red-700 text-white px-4 rounded-lg"
                                        @click="removeIngredient(index, $event)">
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
                    class="mt-6 bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 basis-2/3">
                    <!-- Schritte -->
                    Schritte
                    <InputError :message="form.errors.steps"/>
                    <div class="block">
                        <div id="recipe-step-list" class="flex flex-col space-y-4 mt-4 empty:mt-0">
                            <div v-for="(step, index) in form.steps" class="flex space-x-2">
                                <Textarea v-model="step.description" class="w-full"/>
                                <button class="bg-red-500 hover:bg-red-600 focus:bg-red-700 text-white p-4 rounded-lg"
                                        @click="removeStep(index, $event)">
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
                class="mt-6 bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4">
                <PrimaryButton :disables="form.processing">
                    Speichern
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
