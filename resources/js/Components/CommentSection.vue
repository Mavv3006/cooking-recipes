<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Comment from "@/Components/Comment.vue";
import SectionHeading from "@/Components/SectionHeading.vue";

const props = defineProps({
    comments: Array,
    recipeId: Number
});

const commenting = ref(false);

const commentCreateForm = useForm({
    _method: 'POST',
    comment: '',
    recipe_id: props.recipeId
});

const submitCreateForm = () => {
    console.log('submitting the create form', commentCreateForm);
    commentCreateForm.post(route('comment.create'), {onSuccess: () => commenting.value = false});
    commentCreateForm.reset();
};

</script>

<template>
    <SectionHeading>Kommentare</SectionHeading>

    <div class="flex justify-center mt-4">
        <button
            v-if="commenting===false"
            class="border border-green-900 rounded-lg py-2 px-4 hover:bg-green-900/20 hover:border-green-900/30 focus:ring active:bg-green-900/30 hover:shadow-sm"
            @click="commenting=true">
            <span class="fa-solid fa-pen pr-2"></span>
            Kommentar schreiben
        </button>
    </div>

    <form v-if="commenting===true" @submit.prevent="submitCreateForm">
        <textarea
            v-model="commentCreateForm.comment"
            class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
        <InputError :message="commentCreateForm.errors.comment" class="mt-2"/>
        <div class="space-x-2">
            <PrimaryButton class="mt-4">Save</PrimaryButton>
            <button class="mt-4"
                    @click="commenting = false; commentCreateForm.reset(); commentCreateForm.clearErrors()">
                Cancel
            </button>
        </div>
    </form>

    <div class="flex space-y-8 flex-col mt-4">
        <Comment v-for="comment in comments" :key="comment.id" :comment="comment"></Comment>
    </div>
</template>
