<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <div class="flex">
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
            {{ problem.difficulty.difficulty }}
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
