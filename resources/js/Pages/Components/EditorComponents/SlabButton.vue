<template lang="">
    <button type="button" class="
    bg-leetcode-backgroundlight px-2 py-1 border-2 border-opacity-0 border-leetcode-text rounded-xl
    hover:border-opacity-80">
        {{ value }}
    </button>
</template>
<script>
export default {
    props: {
        value: String,
    },
}
</script>
