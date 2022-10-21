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
    steps: [{description: ''}],
});

const submitForm = () => {
    form.post(route('recipes.store'));
}

const addStep = (event) => {
    if (event) event.preventDefault();
    form.steps.push({description: ''});
};

const removeStep = (index, event) => {
    if (event) event.preventDefault();
    form.steps.splice(index, 1);
};
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
                        />
                        <InputError :message="form.errors.title"/>
                    </div>
                    <div>
                        <!-- Beschreibung -->
                        <InputLabel for="title">Beschreibung des Rezepts</InputLabel>
                        <Textarea
                            id="title"
                            v-model="form.description"
                            type="text"
                            class="w-full"
                        />
                        <InputError :message="form.errors.title"/>
                    </div>
                </div>
                <!-- Tags (Zukunft) -->
            </section>

            <section
                class="mt-6 bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-xl sm:rounded-lg pt-4 pb-4">
                <!-- Schritte -->
                Schritte
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
                        class="mt-4 w-full bg-indigo-200 h-16 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"
                        @click="addStep($event)"
                    >
                        Weiteren Schritt hinzufügen
                    </button>
                </div>
            </section>

            <section>
                <!-- Zutaten -->
            </section>

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
