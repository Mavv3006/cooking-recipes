<script setup>
// import TextInput from "@/Components/TextInput.vue";
import SectionHeading from "@/Components/SectionHeading.vue";

const props = defineProps({
    ingredients: Array
})
</script>

<template>
    <SectionHeading>Zutaten</SectionHeading>

    <!--    <div>-->
    <!--        <button>-->
    <!--            <i class="fa-solid fa-minus"></i>-->
    <!--        </button>-->
    <!--        <TextInput></TextInput>-->
    <!--        <button>-->
    <!--            <i class="fa-solid fa-plus"></i>-->
    <!--        </button>-->
    <!--        <span>Portionen</span>-->
    <!--    </div>-->

    <div v-for="ingredient in ingredients" class="flex space-x-4 mx-8">
        <div class="text-right">{{ ingredient.quantity }} {{ ingredient.uom }}</div>
        <div>{{ ingredient.name }}</div>
    </div>
</template>
