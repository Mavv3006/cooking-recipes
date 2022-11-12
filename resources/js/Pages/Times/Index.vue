<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    times: Array,
    timeUnits: Array
})
</script>

<template>
    <AppLayout title="Zeiten">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Zeiten
            </h2>
        </template>

        <section
            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-6">
            <h3>Welche Zeiten gibt es?</h3>

            <table class="table-auto w-1/4">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody class="text-center">
                <tr v-for="time in times">
                    <td>{{ time.id }}</td>
                    <td>{{ time.name }}</td>
                </tr>
                </tbody>
            </table>
        </section>
        <section
            class="bg-white max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-6">
            <h3>Verfügbare Zeit-Einheiten</h3>

            <table class="table-auto w-1/4">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kurz</th>
                    <th>Lang</th>
                </tr>
                </thead>
                <tbody class="text-center">
                <tr v-for="unit in timeUnits">
                    <td>{{ unit.id }}</td>
                    <td>{{ unit.short }}</td>
                    <td>{{ unit.long }}(n)</td>
                </tr>
                </tbody>
            </table>
        </section>
    </AppLayout>
</template>
