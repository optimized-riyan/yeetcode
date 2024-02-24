<template>
    <div>
        <p>{{ parameterName }}</p>
        <p>{{ parameterContent }}</p>
    </div>
</template>
<script>
export default {
    props: {
        parameterName: String,
        parameterContent: String
    }
}
</script>
<style lang="">

</style>
