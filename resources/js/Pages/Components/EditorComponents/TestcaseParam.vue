<template>
    <div>
        <input type="text" :value="parameterContentData" @input="inputChanged">
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
        parameterName: String,
        parameterContent: String,
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
