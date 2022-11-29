<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({time: Object})

const editTimeForm = useForm({name: props.time.name})
const isEditing = ref(false);

const deleteTime = (time) => {
    console.info('deleting time: ' + time);
    useForm({_method: 'DELETE'}).delete(route('times.delete', {'time': time}));
};


const submitNewTimeName = () => {
    editTimeForm.put(route('times.update', {'time': props.time.id}), {
        onSuccess: () => {
            editTimeForm.reset();
            isEditing.value = false;
        }
    });
}

</script>

<template>
    <tr>
        <td>{{ time.id }}</td>
        <td v-if="!isEditing">{{ time.name }}</td>
        <td class="space-x-2" v-if="!isEditing">
            <i class="fa-regular fa-pen-to-square hover:cursor-pointer" @click="isEditing=true"></i>
            <i class="fa-solid fa-trash text-red-700 hover:cursor-pointer"
               @click="deleteTime(time.id)"></i>
        </td>
        <td v-if="isEditing">
            <TextInput class="w-full" v-model="editTimeForm.name"/>
        </td>
        <td v-if="isEditing">
            <button @click="submitNewTimeName">
                <i class="fa-regular px-4 py-1 fa-floppy-disk bg-indigo-200 relative hover:bg-indigo-300 focus:text-white focus:bg-indigo-400 rounded-lg"></i>
            </button>
            <button @click="isEditing=false">
                <i class="fa-solid fa-xmark px-4 py-1 bg-red-300 relative hover:bg-red-400 focus:text-white focus:bg-red-500 rounded-lg"></i>
            </button>
        </td>
    </tr>
</template>
