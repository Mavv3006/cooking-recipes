<script setup>

import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps({recipe: Number})

const form = useForm({
    _method: 'POST',
    image: null,
});

const submit = () => {
    try {
        form.post(route('image.store', {'recipe': props.recipe}));
    } catch (error) {
        console.error(error);
        form.reset();
    }
}

</script>

<template>

    <form @submit.prevent="submit">
        <div>
            <label for="image">Bild bitte hochladen</label> <br>
            <input id="image" accept="image/*" name="image" type="file" @input="form.image = $event.target.files[0]">
        </div>
        <div class="mt-2">
            <button type="submit">Speichern</button>
        </div>
    </form>

</template>
