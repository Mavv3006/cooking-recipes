<script setup>
import SectionHeading from "@/Components/SectionHeading.vue";
import RecipeActionButtons from "@/Components/RecipeActionButtons.vue";

const props = defineProps({
    steps: Array,
    times: Object,
    isFavorite: Boolean
});
const timeUnit = (duration, unit) => duration > 1 ? unit + 'n' : unit;
</script>

<template>
    <SectionHeading>Zubereitung</SectionHeading>

    <div v-if="times.length > 0" class="mt-4 text-sm flex gap-2 flex-wrap">
        <div
            v-for="time in times"
            class="bg-green-900/20 px-3 py-1 rounded-full w-fit hover:bg-green-900/10 transition">
            <i class="fa-solid fa-clock mr-1"></i>
            {{ time.time.name }} ca. {{ time.duration }} {{ timeUnit(time.duration, time.times_unit.long) }}
        </div>
    </div>

    <div class="mt-4">
        <div v-for="(step, index) in steps" class="mt-2 first:mt-0">
            <div class="hidden italic">Schritt No. {{ index }}</div>
            <div>{{ step.description }}</div>
        </div>
    </div>

    <RecipeActionButtons :is-favorite="isFavorite"/>
</template>
