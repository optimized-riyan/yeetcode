<template lang="">
    <div :class="`bg-leetcode-backgroundlight ${textColor} px-2 py-1 rounded-lg text-sm`">
        <slot />
    </div>
</template>
<script>
export default {
    props: {
        textColor: String,
    }
}
</script>
