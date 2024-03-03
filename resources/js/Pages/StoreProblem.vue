<script setup>
</script>
<template lang="">
    <div class="bg-leetcode-background">
        <form class="text-leetcode-text w-2/5 mx-auto h-screen flex-col" autocomplete="off">
            <!-- name -->
            <div>
                <label for="name">Name: </label>
                <input type="text" id="name" v-model="form.name">
                <p v-if="$page.props.errors.name">{{ $page.props.errors.name }}</p>
            </div>
            <!-- description -->
            <div>
                <label for="description">Description: </label>
                <textarea id="description" cols="30" rows="10" v-model="form.description"></textarea>
            </div>
            <!-- scaffholding -->
            <div class="h-1/2">
                <p>Scaffholding: </p>
                <div ref="aceEditor" class="h-full">console.log('Hello World!');</div>
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
                <!-- dropdown button -->
                <button type="button" @click="()=>{topics_dropdown = !topics_dropdown}">Add Topics</button>
                <div v-if="topics_dropdown">
                    <!-- filter -->
                    <div>
                        <div>
                            <input type="text" @keyup.enter="fetchTopicsWithFilter" v-model="topics_text">
                        </div>
                        <div>
                            <button type="button" @click="fetchTopicsWithFilter">Find</button>
                        </div>
                    </div>
                    <!-- selected topics -->
                    <div>
                        <div>
                            <div>
                                <input type="text" @keyup.enter="pushNewTopic" ref="new_topic">
                            </div>
                            <div>
                                <button type="button" @click="pushNewTopic">Add New Topic</button>
                            </div>
                        </div>
                        <ul v-for="(topic, index) in form.new_topics" :key="index">
                            <div>{{ topic }}</div>
                            <div><button type="button" @click="()=>{form.new_topics.splice(index, 1)}">Remove</button></div>
                        </ul>
                        <ul v-for="(topic, index) in form.selected_topics" :key="index">
                            {{ topic.name }}
                        </ul>
                    </div>
                    <!-- list of topics -->
                    <div>
                        <ul v-for="(topic, index) in filtered_topics" :key="index">
                            <li>
                                {{ topic.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- similar problems -->
            <div>
                <!-- dropdown button -->
                <button type="button" @click="()=>{sim_prob_dropdown = !sim_prob_dropdown}">Add Similar Problems</button>
                <div v-if="sim_prob_dropdown">
                    <!-- filter -->
                    <div>
                        <div>
                            <input type="text" @keyup.enter="fetchSimilarProblems" v-model="problems_by_title_text">
                        </div>
                        <div>
                            <button type="button" @click="fetchSimilarProblems">Find</button>
                        </div>
                    </div>
                    <!-- selected problems -->
                    <div>
                    </div>
                    <!-- list of problems -->
                    <div>
                        <ul v-for="(problem, index) in problems_by_title" :key="index">
                            <li>
                                {{ problem.name }}
                            </li>
                        </ul>
                    </div>
                </div>
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
            <button type="button" @click="submitForm">Submit New Problem</button>
        </form>
    </div>
</template>
<script>
import ace from 'ace-builds';
import 'ace-builds/esm-resolver';
import workerJavascriptUrl from "ace-builds/src-noconflict/worker-javascript?url";
import 'ace-builds/src-noconflict/theme-cloud_editor_dark';
import 'ace-builds/src-noconflict/mode-javascript';
import 'ace-builds/src-noconflict/keybinding-vim';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

ace.config.setModuleUrl('ace/mode/javascript_worker', workerJavascriptUrl);

export default {
    data() {
        return {
            editor: null,
            form: {
                name: '',
                description: '',
                examples: [],
                constraints: [],
                testcases: [],
                selected_topics: [],
                new_topics: [],
                similar_problems: [],
                hints: [],
            },
            sim_prob_dropdown: false,
            problems_by_title_text: '',
            problems_by_title: [],
            topics_dropdown: false,
            topics_text: '',
            filtered_topics: [],
        }
    },
    methods: {
        submitForm() {
            router.post(route('problems.store'), this.form);
        },
        async fetchSimilarProblems() {
            try {
                const data = await (await fetch(`http://localhost:8000/api/get-problems?probs=${encodeURIComponent(this.problems_by_title_text)}`)).json();
                this.problems_by_title = data.problems;
            }
            catch (err) {
                console.error(err);
            }
        },
        async fetchTopicsWithFilter() {
            try {
                const data = await (await fetch(`http://localhost:8000/api/get-topics?topics=${encodeURIComponent(this.topics_text)}`)).json();
            }
            catch (err) {
                console.error(err);
            }
        },
        pushNewTopic() {
            this.form.new_topics.push(this.$refs.new_topic.value);
        }
    },
    props: {
        errors: null,
    },
    mounted() {
        this.editor = ace.edit(this.$refs.aceEditor, {
            minLines: 10,
            fontSize: 12,
            showPrintMargin: false,
            theme: 'ace/theme/cloud_editor_dark',
            mode: 'ace/mode/javascript',
            // keyboardHandler: 'ace/keyboard/vim',
            tabSize: 4
        });
    },
}
</script>
