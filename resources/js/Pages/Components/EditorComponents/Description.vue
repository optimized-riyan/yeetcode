<script setup>
</script>
<template lang="">
    <div class="flex flex-col w-1/3 bg-slate-400 overflow-auto">
        <!-- problem name and number -->
        <div class="text-red-700">
            <span>{{ problem.id }}</span>. {{ problem.name }}
        </div>
        <p>
            {{ problem.description.brief }}
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
<style lang="">

</style>
