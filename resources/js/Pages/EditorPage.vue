<script setup>
defineProps({ problem: Object });
</script>
<template lang="">
    <!-- entire page -->
    <div class="flex flex-col h-screen">
        <!-- titlebar -->
        <div class="bg-leetcode-background h-10 shrink-0"></div>
        <!-- left and right panel -->
        <div class="flex">
            <!-- left panel -->
            <div class="flex flex-col w-1/3 bg-slate-400 overflow-auto">
                <p>
                    {{ problem.description.brief }}
                </p>
            </div>
            <!-- right panel -->
            <div class="flex flex-col grow">
                <!-- editor -->
                <div class="h-2/3 bg-leetcode-green"></div>
                <!-- console -->
                <div class="grow"></div>
            </div>
        </div>
    </div>
</template>
<script>
export default {};
</script>
<style lang=""></style>
