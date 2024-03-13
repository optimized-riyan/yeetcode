<template>
    <div>
        <input type="text" :value="testcaseArray[currentTestcase].testcase" @input="inputChanged">
    </div>
</template>
<script>
export default {
    data() {
        return {
            parameterContentData: this.parameterContent,
        }
    },
    emits: ['sync'],
    props: {
        testcaseArray: Array,
        currentTestcase: Number,
    },
    methods: {
        inputChanged(event) {
            this.parameterContentData = event.target.value;
            this.$emit('sync', event.target.value);
        }
    },
}
</script>
