<script setup>
</script>
<template lang="">
    <form @submit.prevent="submit" class="bg-leetcode-background text-leetcode-text">
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
        </div>
        <button type="button">Add Example</button>
        <!-- testcases -->
        <div>
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
        </div>
        <button type="button">Add Hint</button>
        <!-- constraints -->
        <div>
        </div>
        <button type="button">Add Constraint</button>
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
                testcases: [],
                topics: [],
                similar_problems: [],
                hints: [],
                constraints: [],
            }
        }
    }
}
</script>
<style lang="">

</style>
