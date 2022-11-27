<script setup>
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    ingredients: Array
})
</script>

<template>
    <div>Zutaten</div>

    <div>
        <button>
            <i class="fa-solid fa-minus"></i>
        </button>
        <TextInput></TextInput>
        <button>
            <i class="fa-solid fa-plus"></i>
        </button>
        <span>Portionen</span>
    </div>

    <table>
        <thead>
        <tr>
            <td>Anzahl</td>
            <td>Einheit</td>
            <td>Beschreibung (+Plural)</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="ingredient in ingredients">
            <td>{{ ingredient.quantity }}</td>
            <td>{{ ingredient.uom }}</td>
            <td>{{ ingredient.name }}</td>
        </tr>
        </tbody>
    </table>
</template>
