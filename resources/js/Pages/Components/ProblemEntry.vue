<template>
    <div class="flex">
        <!-- status -->
        <div>
            haiyah
        </div>
        <!-- name -->
        <div>
            <Link :href="route('problems.show', { problem })">{{ problem.name }}</Link>
        </div>
        <!-- difficulty -->
        <div>
            {{ problem.difficulty.difficulty }}
        </div>
    </div>
</template>

<script>
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        problem: Object
    }
}
</script>
