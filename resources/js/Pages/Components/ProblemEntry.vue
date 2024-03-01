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
import { Link } from '@inertiajs/vue3';

export default {
    props: {
        problem: Object
    }
}
</script>
