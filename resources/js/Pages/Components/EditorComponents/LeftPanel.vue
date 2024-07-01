<template lang="">
    <div class="flex flex-col ml-4 border-leetcode-backgroundlight">
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
                    <div class="my-4 border-l-2 border-leetcode-backgroundlight">
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
        <!-- gap between constraints and dropdowns -->
        <div class="mt-9"></div>
        <!-- topics -->
        <div v-if="problem.topics && problem.topics.length > 0" class="mr-4 py-2 border-y-2 border-inherit">
            <ContentDropdown title="Topics">
                <ul class="flex ml-2 mt-2 gap-2">
                    <li>
                        <Tile>Array</Tile>
                    </li>
                    <li>
                        <Tile>Math</Tile>
                    </li>
                </ul>
            </ContentDropdown>
        </div>
        <!-- hints -->
        <div v-if="problem.hints && problem.hints.length > 0">
            <ul class="flex flex-col mr-4 py-2 border-y-2 border-leetcode-backgroundlight">
                <li v-for="(hint, index) in problem.hints" :key="hint.hint_number">
                    <ContentDropdown :title="`Hint ${index+1}`">
                        <div class="ml-3 mt-2">
                            {{ hint.brief }}
                        </div>
                    </ContentDropdown>
                </li>
            </ul>
        </div>
        <!-- similar problems -->
        <div v-if="problem.similarProblems && problem.similarProblems.length > 0" class="mr-4 py-2 border-y-2 border-inherit">
            <ContentDropdown title="Similar Problems">
                <ul class="flex ml-2 mt-2 gap-2">
                    <li>
                        <Tile>Sum of Array</Tile>
                    </li>
                    <li>
                        <Tile>Elements</Tile>
                    </li>
                </ul>
            </ContentDropdown>
        </div>
        <!-- gap after dropdowns -->
        <div class="mb-16"></div>
    </div>
</template>
<script>
import ContentDropdown from './ContentDropdown.vue';
import Tile from '@/Pages/Components/Tile.vue';

export default {
    props: {
        problem: Object,
    },
    components: {
        ContentDropdown,
        Tile,
    },
}
</script>
