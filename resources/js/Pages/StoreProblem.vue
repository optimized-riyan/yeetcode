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
            <ul v-for="(example, index) in form.examples" :key="index">
                <li>
                    {{ example }}
                </li>
            </ul>
            <span>Add Example: </span><textarea ref="examplesTextarea" name="examples" cols="30" rows="10"></textarea>
            <button type="button" @click="()=>{form.examples.push(this.$refs.examplesTextarea.value)}">Add</button>
        </div>
        <!-- testcases -->
        <div>
            <ul v-for="(testcase, index) in form.testcases" :key="index">
                <li>
                    {{ testcase }}
                </li>
            </ul>
            <span>Add Testcase: </span><textarea ref="testcasesTextarea" name="testcases" cols="30" rows="10"></textarea>
            <button type="button" @click="()=>{form.examples.push(this.$refs.testcasesTextarea.value)}">Add</button>
        </div>
        <button type="button">Add Testcase</button>
        <!-- topics -->
        <div>
        </div>
        <!-- similar problems -->
        <div>
        </div>
        <button type="button">Add Similar Problem</button>
        <!-- hints -->
        <div>
            <ul v-for="(hint, index) in form.hints" :key="index">
                <li>
                    {{ hint }}
                </li>
            </ul>
            <span>Add Hint: </span><textarea ref="hintsTextarea" name="hints" cols="30" rows="10"></textarea>
            <button type="button" @click="()=>{form.examples.push(this.$refs.hintsTextarea.value)}">Add</button>
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

        }
    }
}
</script>
<style lang="">

</style>
