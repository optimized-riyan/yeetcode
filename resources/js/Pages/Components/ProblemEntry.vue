<template>
    <div class="flex flex-col">
        <!-- status -->
        <div>
            haiyah
        </div>
        <!-- name -->
        <div>
            {{ problem.name }}
        </div>
        <!-- difficulty -->
        <div>
            {{ problem.difficulty }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        problem: Object
    }
}
</script>

<style lang="">

</style>
