<script setup>
import Avatar from "vue-boring-avatars";

const props = defineProps({
    username: String
})

</script>

<template>
    <div>Rezept erstellt von:</div>
    <div>
        <Avatar/>
    </div>
    <div>{{ username }}</div>
</template>
