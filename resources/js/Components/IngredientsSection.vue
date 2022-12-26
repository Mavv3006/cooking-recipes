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

    <div class="mt-4">
        <div v-for="ingredient in ingredients"
             class="flex space-x-4 odd:bg-green-900/20 odd:rounded-lg mt-1 first:mt-0 py-1">
            <div class="text-right basis-1/4">{{ ingredient.quantity }} {{ ingredient.uom }}</div>
            <div class="basis-3/4">{{ ingredient.name }}</div>
        </div>
    </div>
</template>
