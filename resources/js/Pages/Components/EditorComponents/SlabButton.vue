<template lang="">
    <button type="button" :class="
    `bg-leetcode-backgroundlight px-2 py-1 border-2 border-opacity-0 border-leetcode-text rounded-xl
    ${classAttributesIfActive} ${ rotateContentAround ? 'rotate-180' : '' }`">
        {{ value }}
        <slot />
    </button>
</template>
<script>
export default {
    props: {
        value: String,
        isActive: {
            type: Boolean,
            default: false,
        },
        rotateContentAround: Boolean,
    },
    computed: {
        classAttributesIfActive() {
            return (this.isActive) ? "border-opacity-80" : "hover:border-opacity-80";
        }
    }
}
</script>
