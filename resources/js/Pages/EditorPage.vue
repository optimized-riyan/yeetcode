<script setup>
defineProps({ problem: Object, trivialTestcases: Object });
</script>
<template lang="">
    <!-- entire page -->
    <div class="flex flex-col h-screen">
        <!-- titlebar -->
        <div class="bg-leetcode-background h-10 shrink-0">

        </div>
        <!-- left and right panel -->
        <div class="flex grow overflow-auto">
            <!-- left panel -->
            <Description :problem="problem"></Description>
            <!-- right panel -->
            <div class="flex flex-col grow">
                <!-- editor -->
                <div class="h-2/3 bg-leetcode-green">
                    <textarea cols="80" rows="18"></textarea>
                </div>
                <!-- console -->
                <div class="grow">

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Description from './Components/EditorComponents/Description.vue';
export default {
    components: {
        Description,
    }
};
</script>
<style lang=""></style>
