<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import TimesTableRow from "@/Components/TimesTableRow.vue";

const props = defineProps({
    times: Array,
    timeUnits: Array
})

const addTimeForm = useForm({
    name: ''
})

const isAddingTimes = ref(false);

const addTime = (event) => {
    if (event) event.preventDefault();
    addTimeForm.post(route('times.store'), {
        onSuccess: () => {
            addTimeForm.reset();
            isAddingTimes.value = false;
        }
    });
}

</script>

<template>
    <AppLayout title="Zeiten">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Zeiten
            </h2>
        </template>

        <div class="flex max-w-7xl space-x-32 mx-auto">
            <section
                class="bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-6">
                <h3>Welche Zeiten gibt es?</h3>

                <table class="table-auto mx-auto mt-2 w-full">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Aktionen</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <TimesTableRow v-for="time in times" :time="time"/>
                    <tr>
                        <td colspan="3"
                            @click="isAddingTimes=true"
                            class="bg-indigo-200 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"
                            v-if="!isAddingTimes">
                            <i class="fa-regular fa-plus"></i>
                        </td>
                        <td v-if="isAddingTimes" colspan="3">
                            <form @submit.prevent="addTime" class="flex space-x-2">
                                <TextInput class="w-full" v-model="addTimeForm.name"></TextInput>
                                <button type="submit">
                                    <i class="fa-regular px-4 py-1 fa-floppy-disk bg-indigo-200 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"></i>
                                </button>
                                <button @click="isAddingTimes=false">
                                    <i class="fa-solid fa-xmark px-4 py-1 bg-red-300 relative hover:bg-red-400 focus:text-white focus:bg-red-500 rounded-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section
                class="bg-white w-full mx-auto sm:px-6 lg:px-8 overflow-hidden shadow-md sm:rounded-lg pt-4 pb-4 mt-6">
                <h3>Verfügbare Zeit-Einheiten</h3>

                <table class="table-auto">
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
        </div>
    </AppLayout>
</template>
