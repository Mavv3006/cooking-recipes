<script setup>
import SectionHeading from "@/Components/SectionHeading.vue";

const props = defineProps({
    steps: Array
});

</script>

<template>
    <SectionHeading>Zubereitung</SectionHeading>

    <div v-for="(step, index) in steps" class="mb-2">
        <div class="italic">Schritt No. {{ index }}</div>
        <div>{{ step.description }}</div>
    </div>
</template>
