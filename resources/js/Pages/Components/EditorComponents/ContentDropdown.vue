<template lang="">
    <div>
        <div class="flex w-full justify-between bg-leetcode-backgroundlight py-3" @click="dropdownActive = !dropdownActive">
            <div class="ml-6">{{ title }}</div>
            <div class="mr-6"><i class="fa-solid fa-chevron-down"></i></div>
        </div>
        <slot v-if="dropdownActive" />
    </div>
</template>
<script>
export default {
    props: {
        title: String,
    },
    data() {
        return {
            dropdownActive: false,
        }
    }
}
</script>
