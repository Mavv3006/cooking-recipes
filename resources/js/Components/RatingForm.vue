<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/inertia-vue3";

const form = useForm({
    stars: 0
})

</script>

<template>

    <form>
        <InputLabel for="stars">Anzahl Sterne. Von 1 bis 5</InputLabel>
        <TextInput
            id="stars"
            v-model="form.stars"
            type="number"
            placeholder="z.B.: 1"
        />
        <InputError :message="form.errors.stars"/>
        <PrimaryButton :disables="form.processing" class="ml-4">
            Speichern
        </PrimaryButton>
    </form>

</template>
