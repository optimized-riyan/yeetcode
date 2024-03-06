<script setup>
</script>
<template lang="">
    <div class="bg-leetcode-background">
        <form class="text-leetcode-textdark w-2/3 mx-auto h-screen flex-col overflow-y-auto" autocomplete="off">
            <!-- name -->
            <div>
                <label for="name">Name: </label>
                <input type="text" id="name" v-model="form.name">
                <p v-if="errors.name">{{ errors.name }}</p>
            </div>
            <!-- difficulty -->
            <div>
                <div>
                    <button type="button" @click="()=>{form.difficulty = (form.difficulty + 1) % 3 + 1}">Cycle Difficulty</button>
                </div>
                <div>
                    <p>{{ difficulty }}</p>
                </div>
            </div>
            <!-- description -->
            <div>
                <label for="description">Description: </label>
                <textarea id="description" cols="30" rows="10" v-model="form.description"></textarea>
                <p v-if="errors.description">{{ errors.description }}</p>
            </div>
            <!-- scaffholding -->
            <div class="h-1/2 flex flex-col">
                <p>Scaffholding: </p>
                <div class="grow">
                    <div ref="aceEditor" class="h-full" @input="syncAceEditor"></div>
                </div>
                <p v-if="errors.scaffholding">{{ errors.scaffholding }}</p>
            </div>
            <!-- tc parameters -->
            <div>
                <div>
                    <button type="button" @click="()=>{form.tc_parameters.push({param: ''})}">Add Testcase Parameter</button>
                </div>
                <!-- tc params -->
                <div>
                    <ul v-for="(param, index) in form.tc_parameters" :key="index">
                        <li>
                            <div>
                                <label :for="'param'+index">Parameter {{ index+1 }}</label>
                                <input type="text" :id="'param'+index" v-model="form.tc_parameters[index].param">
                            </div>
                            <div>
                                <button type="button" @click="()=>{form.tc_parameters.splice(index, 1)}">Remove</button>
                            </div>
                        </li>
                    </ul>
                </div>
                <p v-if="errors.tc_parameters">{{ errors.tc_parameters }}</p>
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
                <p v-if="errors.examples">{{ errors.examples }}</p>
            </div>
            <!-- testcases -->
            <div>
                <button type="button" @click="()=>{form.testcases.push({testcase: '', output: '', is_trivial: false})}">Add Testcase</button>
                <ul v-for="(testcase, index) in form.testcases" :key="index">
                    <li>
                        <div>
                            <label :for="'testcase'+index">Testcase {{ index+1 }}</label>
                            <textarea :id="'testcase'+index" cols="30" rows="10" v-model="testcase.testcase"></textarea>
                        </div>
                        <div>
                            <label :for="'output'+index">Expected Output</label>
                            <textarea :id="'output'+index" cols="30" rows="10" v-model="testcase.output"></textarea>
                        </div>
                        <div>
                            <label :for="'checkbox'+index">Testcase is trivial</label>
                            <input type="checkbox" :id="'checkbox'+index" v-model="testcase.is_trivial">
                        </div>
                        <div>
                            <button type="button" @click="()=>{form.testcases.splice(index, 1)}">Remove</button>
                        </div>
                    </li>
                </ul>
                <p v-if="errors.testcases">{{ errors.testcases }}</p>
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
                <p v-if="errors.selected_topics">{{ errors.selected_topics }}</p>
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
                        <ul v-for="(problem, index) in form.similar_problems" :key="index">
                            <li @click="removeFromSimilarProblems(problem, index)" class="cursor-pointer">
                                {{ problem.name }}
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <!-- list of problems -->
                    <div>
                        <ul v-for="(problem, index) in problems_by_title" :key="index">
                            <li @click="addToSimilarProblems(problem, index)" class="cursor-pointer">
                                {{ problem.name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <p v-if="errors.similar_problems">{{ errors.similar_problems }}</p>
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
                <p v-if="errors.constraints">{{ errors.constraints }}</p>
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
                <p v-if="errors.hints">{{ errors.hints }}</p>
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
                name: 'Sum Of All Elements',
                difficulty: 1,
                description: 'Return the sum of all elements in the array.',
                scaffholding: '// write your code here',
                tc_parameters: [{ param: 'nums' }],
                examples: [{ input: '[1, 2, 3]', output: '6', explaination: '' }],
                constraints: [{ constraint: 'unrestricted' }],
                testcases: [{ testcase: '[1, 2, 3]', output: '6', is_trivial: false }],
                selected_topics: [],
                new_topics: ['Array', 'Math'],
                similar_problems: [],
                hints: [{ hint: 'Just use the sum() function in python.' }],
            },
            sim_prob_dropdown: true,
            problems_by_title_text: '',
            problems_by_title: [],
            selected_problems: new Set(),
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
                data.problems.filter(problem => !this.selected_problems.has(problem.id));
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
        },
        syncAceEditor() {
            this.form.scaffholding = this.editor.getValue();
        },
        addToSimilarProblems(problem, index) {
            this.form.similar_problems.push(problem);
            this.selected_problems.add(problem.id);
            this.problems_by_title.splice(index, 1);
        },
        removeFromSimilarProblems(problem, index) {
            this.form.similar_problems.splice(index, 1);
            this.selected_problems.delete(problem.id);
            this.problems_by_title.unshift(problem);
        }
    },
    computed: {
        difficulty() {
            switch (this.form.difficulty) {
                case 0: return 'Easy';
                case 1: return 'Medium';
                case 2: return 'Hard';
                default: {
                    this.form.difficulty = 0;
                    return 'Easy';
                }
            }
        },
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
        this.editor.setValue(this.form.scaffholding);
        this.editor.gotoLine(1);
    },
}
</script>
