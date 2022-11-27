<script setup>
const props = defineProps({
    steps: Array
});

</script>

<template>
    <div>Zubereitung</div>

    <div v-for="(step, index) in steps">
        <div>{{ index }}</div>
        <div>{{ step.description }}</div>
    </div>
</template>
