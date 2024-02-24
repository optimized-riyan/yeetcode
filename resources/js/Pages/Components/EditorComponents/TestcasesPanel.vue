<template lang="">
    <ul>
        <li v-for="(tcParam, index) in tcParameters" :key="index">
            <TestcaseParam :parameterName="tcParameters[index]" :parameterContent="testcaseArray[currentTestcase].testcase"></TestcaseParam>
        </li>
    </ul>
</template>
<script>
import TestcaseParam from './TestcaseParam.vue';
export default {
    props: {
        tcParameters: Array
    },
    components: {
        TestcaseParam,
    }
}
</script>
<style lang="">

</style>
