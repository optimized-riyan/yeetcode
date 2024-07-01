<template lang="">
    <form action="" method="post">
        <input type="text" placeholder="Problem name . . ." class="rounded-lg bg-leetcode-backgroundlight border-none text-leetcode-text focus:outline-none h-10">
        <button type="button" @click="searchProblems" class="ml-2 hover:text-leetcode-green-light"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</template>
<script>
export default {
    methods: {
        searchProblems() {

        }
    },
}
</script>
