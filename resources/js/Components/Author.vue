<script setup>
import Avatar from "vue-boring-avatars";
import SectionHeading from "@/Components/SectionHeading.vue";

const props = defineProps({
    username: String
})

</script>

<template>
    <SectionHeading>Tags / Kategorien</SectionHeading>
    <div>Rezept erstellt von:</div>
    <div class="flex space-x-4 items-center">
        <Avatar/>
        <div>{{ username }}</div>
    </div>
</template>
