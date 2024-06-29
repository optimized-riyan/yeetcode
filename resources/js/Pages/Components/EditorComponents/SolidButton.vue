<template lang="">
    <button type="button" :class="`${bgColor} ${textColor} ${bgColorHover} ${textColorHover}`">
        <slot />
    </button>
</template>
<script>
export default {
    props: {
        bgColor: {
            type: String,
            default: "bg-leetcode-backgroundlighter",
        },
        textColor: {
            type: String,
            default: "text-leetcode-green",
        },
        bgColorHover: {
            type: String,
            default: "hover:bg-leetcode-backgroundlight",
        },
        textColorHover: {
            type: String,
            default: "hover:text-black",
        }
    }
}
</script>
 <!-- hover:${textColorHover} -->
