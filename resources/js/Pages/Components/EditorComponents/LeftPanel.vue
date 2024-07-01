<template lang="">
    <div class="flex flex-col ml-4">
        <!-- problem name and number -->
        <div class="text-leetcode-red">
            <span>{{ problem.id }}</span>. {{ problem.name }}
        </div>
        <!-- description -->
        <div>
            {{ problem.description }}
        </div>
        <!-- examples -->
        <div class="my-9">
            <ul>
                <li v-for="(example, index) in problem.examples" :key="index">
                    <p class="font-bold text-md">Example {{ index+1 }}:</p>
                    <!-- extra div for border -->
                    <div class="my-4 border-l-2">
                        <!-- example info -->
                        <div class="ml-3">
                            <!-- input -->
                            <div>
                                <span class="font-bold">Input: </span>{{ example.input }}
                            </div>
                            <!-- output -->
                            <div>
                                <span class="font-bold">Output: </span>{{ example.output }}
                            </div>
                            <!-- explaination -->
                            <div v-if="example.explaination">
                                <span class="font-bold">Explaination: </span>{{ example.explaination }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- constraints -->
        <div>
            <p class="font-bold">Constraints:</p>
            <!-- constraints info -->
            <ul class="list-disc pl-6">
                <li v-for="(constraint, index) in problem.constraints" :key="index">
                    {{ constraint.constraint }}
                </li>
            </ul>
        </div>
        <!-- topics -->
        <div>

        </div>
        <!-- hints -->
        <div>

        </div>
        <!-- similar problems -->
        <div>

        </div>
    </div>
</template>
<script>
export default {
    props: {
        problem: Object,
    }
}
</script>
