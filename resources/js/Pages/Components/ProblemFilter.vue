<template lang="">
    <input type="text" placeholder="Problem name . . ." v-model="name"
    class="rounded-lg bg-leetcode-backgroundlight border-none text-leetcode-text focus:outline-none h-10"
    >
    <button type="button" @click="redirectToFilteredProblems" class="ml-2 hover:text-leetcode-green-light"><i class="fa-solid fa-magnifying-glass"></i></button>
</template>
<script>
import { router } from "@inertiajs/vue3";

export default {
    data() {
        return {
            name: "",
        }
    },
    methods: {
        redirectToFilteredProblems() {
            router.visit(`/problems?name=${this.name}`);
        }
    },
}
</script>
