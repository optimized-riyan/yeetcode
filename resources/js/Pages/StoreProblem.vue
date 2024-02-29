<script setup>
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>
<template lang="">
    <form class="bg-leetcode-background text-leetcode-text" autocomplete="off">
        <div>
            <label for="name">Name: </label>
            <input type="text" id="name">
        </div>
        <div>
            <label for="description">Description: </label>
            <textarea id="description" cols="30" rows="10"></textarea>
        </div>
        <!-- examples -->
        <div>
            <button type="button" @click="()=>{form.examples.push({input: '', output: '', explaination: ''})}">Add Example</button>
            <ul v-for="(example, index) in form.examples" :key="index">
                <li class="flex-col">
                    <div>
                        <p>Example {{ index+1 }}</p>
                    </div>
                    <div class="flex">
                        <label :for="'exampleInput'+index">Input</label>
                        <input :id="'exampleInput'+index" type="text" v-model="form.examples[index].input">
                    </div>
                    <div class="flex">
                        <label :for="'exampleOutput'+index">Output</label>
                        <input :id="'exampleOutput'+index" type="text" v-model="form.examples[index].output">
                    </div>
                    <div class="flex">
                        <label :for="'exampleExplaination'+index">Explaination</label>
                        <textarea :id="'exampleExplaination'+index" cols="30" rows="5" v-model="form.examples[index].explaination"></textarea>
                    </div>
                    <div>
                        <button type="button" @click="()=>{form.examples.splice(index, 1)}">Remove</button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- testcases -->
        <div>
            <button type="button" @click="()=>{form.testcases.push({testcase: ''})}">Add Testcase</button>
            <ul v-for="(testcase, index) in form.testcases" :key="index">
                <li>
                    <div>
                        <label :for="'testcase'+index">Testcase {{ index+1 }}</label>
                        <textarea :id="'testcase'+index" cols="30" rows="10" v-model="form.testcases[index].testcase"></textarea>
                    </div>
                    <div>
                        <button type="button" @click="()=>{form.testcases.splice(index, 1)}">Remove</button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- topics -->
        <div>
        </div>
        <!-- similar problems -->
        <div>
        </div>
        <button type="button">Add Similar Problem</button>
        <!-- hints -->
        <div>
            <button type="button" @click="()=>{form.hints.push({hint: ''})}">Add Hint</button>
            <ul v-for="(hint, index) in form.hints" :key="index">
                <li>
                    <div>
                        <label :for="'hint'+index">Hint {{ index+1 }}</label>
                        <textarea :id="'hint'+index" cols="30" rows="10" v-model="form.hints[index].hint"></textarea>
                    </div>
                    <div>
                        <button type="button" @click="()=>{form.hints.splice(index, 1)}">Remove</button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- constraints -->
        <div>
            <button type="button" @click="()=>{form.constraints.push({constraint: ''})}">Add Constraint</button>
            <ul v-for="(constraint, index) in form.constraints" :key="index">
                <li>
                    <div>
                        <label :for="'constraint'+index">Constraint {{ index+1 }}</label>
                        <textarea :id="'constraint'+index" cols="30" rows="10" v-model="form.constraints[index].constraint"></textarea>
                    </div>
                    <div>
                        <button type="button" @click="()=>{form.constraints.splice(index, 1)}">Remove</button>
                    </div>
                </li>
            </ul>
        </div>
        <button type="button" @click="submitForm">Submit New Problem</button>
    </form>
</template>
<script>
export default {
    data() {
        return {
            form: {
                name: null,
                description: null,
                examples: [],
                constraints: [],
                testcases: [],
                topics: [],
                similar_problems: [],
                hints: [],
            }
        }
    },
    methods: {
        submitForm() {
            router.post(route('problems.store'));
        },
    }
}
</script>
<style lang="">

</style>
