<template>
    <div class="flex bg-leetcode-backgroundlight p-3 my-1 rounded-lg">
        <!-- status -->
        <div class="ml-2 mr-4">
            <i :class="icon"></i>
        </div>
        <div class="flex justify-between flex-grow">
            <!-- name -->
            <div>
                <Link :href="route('problems.show', { problem })" class="hover:text-leetcode-green-light">{{ problem.name }}</Link>
            </div>
            <!-- difficulty -->
            <div :class="`${getDifficultyColor}`">
                {{ problem.difficulty.difficulty }}
            </div>
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
        problem: Object,
        icon: {
            type: String,
            default: "fa-solid fa-minus",
        },
    },
    computed: {
        getDifficultyColor() {
            switch (this.problem.difficulty.difficulty) {
                case "Easy":
                    return "text-leetcode-green-light";
                case "Medium":
                    return "text-leetcode-yellow";
                case "Hard":
                    return "text-leetcode-red";
                default:
                    return "text-white";
            }
        }
    },
}
</script>
