<template lang="">
    <div class="flex flex-col overflow-auto">
        <!-- problem name and number -->
        <div class="text-leetcode-red">
            <span>{{ problem.id }}</span>. {{ problem.name }}
        </div>
        <p>
            {{ problem.description }}
        </p>
    </div>
</template>
<script>
export default {
    props: {
        problem: Object,
    }
}
</script>
