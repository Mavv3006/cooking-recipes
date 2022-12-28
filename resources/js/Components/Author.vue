<script setup>
import Avatar from "vue-boring-avatars";
import SectionHeading from "@/Components/SectionHeading.vue";

const props = defineProps({
    username: String
})

</script>

<template>
    <SectionHeading>Rezept erstellt von</SectionHeading>
    <div class="flex space-x-4 items-center mt-4">
        <Avatar/>
        <div>{{ username }}</div>
    </div>
</template>
