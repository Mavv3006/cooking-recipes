<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from "@/Components/Dropdown.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import {ref} from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    comment: Object
});

const form = useForm({
    _method: 'PUT',
    comment: props.comment.comment,
    recipe_id: props.comment.recipe_id,
})

const deleteForm = useForm({
    _method: 'DELETE'
});

const editing = ref(false);

const submitCommentUpdateForm = () => {
    form.put(route('comment.update', {'comment': props.comment.id}), {onSuccess: () => editing.value = false});
}

const submitDeleteForm = () => {
    console.log('comment: ' + props.comment.id);
    deleteForm.delete(route('comment.delete', {'comment': props.comment.id}));
};
</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div>
                    {{ comment.user.name }}
                </div>
                <div class="ml-1 flex text-sm items-center space-x-1">
                    <div>{{ dayjs(comment.created_at).fromNow() }}</div>
                    <div v-if="comment.created_at !== comment.updated_at">&middot;</div>
                    <div v-if="comment.created_at !== comment.updated_at">editiert</div>
                </div>
            </div>
            <Dropdown v-if="comment.user.id === $page.props.auth.user?.id">
                <template #trigger>
                    <button class="fa-solid fa-ellipsis"></button>
                </template>
                <template #content>
                    <button
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out"
                        @click="editing = true">
                        Edit
                    </button>
                    <button @click="submitDeleteForm"
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out">
                        Delete
                    </button>
                </template>
            </Dropdown>
        </div>
        <form v-if="editing" @submit.prevent="submitCommentUpdateForm">
            <textarea v-model="form.comment"
                      class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
            <InputError :message="form.errors.comment" class="mt-2"/>
            <div class="space-x-2">
                <PrimaryButton class="mt-4">Save</PrimaryButton>
                <button class="mt-4"
                        @click="editing = false; form.reset(); form.clearErrors()">
                    Cancel
                </button>
            </div>
        </form>
        <p v-else>{{ comment.comment }}</p>
    </div>
</template>
