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
                    <!-- testcases -->
                    <ul class="flex">
                        <li v-for="(testcase, index) in testcaseArray" :key="index">
                            <button type="button" @click="testcaseChange(index)">Testcase {{index+1}}</button>
                        </li>
                    </ul>
                    <!-- testcase content -->
                    <div>
                        <ul>
                            <li v-for="(tcParam, index) in tcParameters" :key="index">
                                <TestcaseParam :parameterName="tcParameters[index]" :parameterContent="testcaseArray[currentTestcase].testcase"></TestcaseParam>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Description from './Components/EditorComponents/Description.vue';
import TestcaseParam from './Components/EditorComponents/TestcaseParam.vue';
export default {
    data() {
        return {
            currentTestcase: 0,
            testcaseArray: Object.values(this.problem.testcases),
            tcParameters: this.problem.tc_parameters.params.split(' '),
        }
    },
    components: {
        Description,
        TestcaseParam,
    },
    computed: {
    },
    methods: {
        testcaseChange(index) {
            this.currentTestcase = index
        }
    }
};
</script>
<style lang=""></style>
