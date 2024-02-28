<script setup>
</script>
<template lang="">
    <form class="bg-leetcode-background text-leetcode-text">
        <div>
            <label for="name">Name: </label>
            <input type="text" name="name">
        </div>
        <div>
            <label for="description">Description: </label>
            <textarea name="description" id="" cols="30" rows="10"></textarea>
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
                        <textarea :id="'hint'+index" cols="30" rows="10"></textarea>
                    </div>
                    <div>
                        <button type="button" @click="()=>{form.hints.splice(index, 1)}">Remove</button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- constraints -->
        <div>
            <ul v-for="(testcase, index) in form.testcases" :key="index">
                <li>
                    {{ testcase }}
                </li>
            </ul>
            <span>Add Constraint: </span><textarea ref="constraintsTextarea" name="constraints" cols="30" rows="10"></textarea>
            <button type="button" @click="()=>{form.examples.push(this.$refs.constraintsTextarea.value)}">Add</button>
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

        },
    }
}
</script>
<style lang="">

</style>
