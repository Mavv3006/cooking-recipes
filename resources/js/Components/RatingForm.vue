<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import NumberInput from "@/Components/NumberInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";

const props = defineProps({'recipe_id': Number});

const form = useForm({
    _method: 'POST',
    stars: null,
    recipe_id: props.recipe_id
});

const is_rating = ref(false);

const submitForm = () => {
    console.debug(form);
    form.post(route('ratings.create'), {
        onSuccess: () => {
            form.reset();
            is_rating.value = false
        }
    });
}
</script>

<template>
    <button
        v-if="is_rating === false"
        class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
        @click="is_rating = true">
        Bewerten
    </button>
    <div class="flex content-center">
        <form v-if="is_rating === true" @submit.prevent="submitForm">
            <InputLabel for="stars">Anzahl Sterne. Von 1 bis 5</InputLabel>
            <NumberInput
                id="stars"
                v-model.number="form.stars"
                max="5"
                min="1"
                placeholder="z.B.: 1"
                step="1"
            />
            <InputError :message="form.errors.stars"/>
            <PrimaryButton :disables="form.processing" class="ml-4">
                Speichern
            </PrimaryButton>
        </form>
        <button
            v-if="is_rating===true"
            class="ml-5 px-3 py-1 bg-gray-200 border border-transparent rounded-md hover:bg-gray-100 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition rounded-md mt-6 mb-1"
            @click="is_rating=false">Abbrechen
        </button>
    </div>
</template>
