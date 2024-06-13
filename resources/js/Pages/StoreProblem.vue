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
            <div class="relative">
                <button type="button" @click="scaffholdingDropdown = !scaffholdingDropdown">{{ selectedLanguage }}</button>
                <div class="absolute top-6 z-10 bg-leetcode-background text-leetcode-text" v-if="scaffholdingDropdown">
                    <ul v-for="(language, index) in availableLanguages" :key="index">
                        <!-- <li @click="()=>{selectedLanguage = language; scaffholdingDropdown = false;}" class="cursor-pointer">{{ language }}</li> -->
                        <li @click="scaffholdingChange(language)" class="cursor-pointer">{{ language }}</li>
                    </ul>
                </div>
            </div>
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
                            <div v-if="errors[`tc_parameters.${index}.param`]">
                                {{ errors[`tc_parameters.${index}.param`] }}
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
                        <div v-if="errors[`examples.${index}.input`]">
                            {{ errors[`examples.${index}.input`] }}
                        </div>
                        <div class="flex">
                            <label :for="'exampleOutput'+index">Output</label>
                            <input :id="'exampleOutput'+index" type="text" v-model="form.examples[index].output">
                        </div>
                        <div v-if="errors[`examples.${index}.output`]">
                            {{ errors[`examples.${index}.output`] }}
                        </div>
                        <div class="flex">
                            <label :for="'exampleExplaination'+index">Explaination</label>
                            <textarea :id="'exampleExplaination'+index" cols="30" rows="5" v-model="form.examples[index].explaination"></textarea>
                        </div>
                        <div v-if="errors[`examples.${index}.explaination`]">
                            {{ errors[`examples.${index}.explaination`] }}
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
                        <div v-if="errors[`testcases.${index}.testcase`]">
                            {{ errors[`testcases.${index}.testcase`] }}
                        </div>
                        <div>
                            <label :for="'output'+index">Expected Output</label>
                            <textarea :id="'output'+index" cols="30" rows="10" v-model="testcase.output"></textarea>
                        </div>
                        <div v-if="errors[`testcases.${index}.output`]">
                            {{ errors[`testcases.${index}.output`] }}
                        </div>
                        <div>
                            <label :for="'checkbox'+index">Testcase is trivial</label>
                            <input type="checkbox" :id="'checkbox'+index" v-model="testcase.is_trivial">
                        </div>
                        <div v-if="errors[`testcases.${index}.is_trivial`]">
                            {{ errors[`testcases.${index}.is_trivial`] }}
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
                    <!-- selected/new topics -->
                    <div>
                        <div>
                            <div>
                                <input type="text" @keyup.enter="pushNewTopic" ref="new_topic">
                            </div>
                            <div>
                                <button type="button" @click="pushNewTopic">Add New Topic</button>
                            </div>
                        </div>
                        <!-- new -->
                        <ul v-for="(topic, index) in form.new_topics" :key="index">
                            <li @click="()=>{form.new_topics.splice(index, 1)}" class="cursor-pointer">
                                <div>
                                    {{ topic }}
                                </div>
                                <div v-if="errors[`new_topics.${index}`]">
                                    {{ errors[`new_topics.${index}`] }}
                                </div>
                            </li>
                        </ul>
                        <!-- selected -->
                        <ul v-for="(topic, index) in form.selected_topics" :key="index" class="cursor-pointer">
                            <li @click="removeFromSelectedTopics(topic, index)">
                                <div>
                                    {{ topic.name }}
                                </div>
                                <div v-if="errors[`selected_topics.${index}.id`] || errors[`selected_topics.${index}.name`]">
                                    {{ errors[`selected_topics.${index}.id`] ?? errors[`selected_topics.${index}.name`] }}
                                </div>
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <!-- list of topics -->
                    <div>
                        <ul v-for="(topic, index) in filtered_topics" :key="index">
                            <li @click="addToSelectedTopics(topic, index)">
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
                                <div>
                                    {{ problem.name }}
                                </div>
                                <div v-if="errors[`similar_problems.${index}.id`] || errors[`similar_problems.${index}.id`]">
                                    {{ errors[`similar_problems.${index}.id`] ?? errors[`similar_problems.${index}.id`] }}
                                </div>
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
                        <div v-if="errors[`constraints.${index}.constraint`]">
                            {{ errors[`constraints.${index}.constraint`] }}
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
                        <div v-if="errors[`hints.${index}.hint`]">
                            {{ errors[`hints.${index}.hint`] }}
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

ace.config.setModuleUrl('ace/mode/javascript_worker', workerJavascriptUrl);

export default {
    data() {
        return {
            editor: null,
            form: {
                name: 'Sum Of All Elements',
                difficulty: 1,
                description: 'Return the sum of all elements in the array.',
                scaffholdings: {},
                tc_parameters: [{ param: 'nums' }],
                examples: [{ input: '[1, 2, 3]', output: '6', explaination: '' }],
                constraints: [{ constraint: 'unrestricted' }],
                testcases: [{ testcase: '[1, 2, 3]', output: '6', is_trivial: false }],
                new_topics: ['Array', 'Math'],
                selected_topics: [],
                similar_problems: [],
                hints: [{ hint: 'Just use the sum() function in python.' }],
            },
            scaffholdingDropdown: false,
            availableLanguages: ['python', 'js', 'php', 'c', 'c++'],
            selectedLanguage: 'python',
            languageIds: {
                'python': 71,
                'js': 63,
                'php': 68,
                'c': 50,
                'c++': 54,
            },
            sim_prob_dropdown: true,
            problems_by_title_text: '',
            problems_by_title: [],
            selected_problems: new Set(),
            topics_dropdown: false,
            topics_text: '',
            filtered_topics: [],
            selected_topics_set: new Set(),
        }
    },
    methods: {
        submitForm() {
            if (this.method == 'post')
                router.post(this.url, this.form);
            else if (this.method == 'put')
                router.put(this.url, this.form);
            else
                console.error('Incorrect verb: ' + this.method);
        },
        async fetchSimilarProblems() {
            try {
                const data = await (await fetch(`/api/get-problems?probs=${encodeURIComponent(this.problems_by_title_text)}`)).json();
                data.problems.filter(problem => !this.selected_problems.has(problem.id));
                this.problems_by_title = data.problems;
            }
            catch (err) {
                console.error(err);
            }
        },
        async fetchTopicsWithFilter() {
            try {
                const data = await (await fetch(`/api/get-topics?topics=${encodeURIComponent(this.topics_text)}`)).json();
                data.topics.filter(topic => !this.selected_topics_set.has(topic.id));
            }
            catch (err) {
                console.error(err);
            }
        },
        pushNewTopic() {
            if (this.$refs.new_topic.value)
                this.form.new_topics.push(this.$refs.new_topic.value);
        },
        syncAceEditor() {
            this.form.scaffholdings[this.languageIds[this.selectedLanguage]] = this.editor.getValue();
            this.editor.setValue(this.form.scaffholdings[this.languageIds[this.selectedLanguage]]);
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
        },
        addToSelectedTopics(topic, index) {
            this.form.selected_topics.push(topic);
            this.selected_topics_set.add(topic.id);
            this.filtered_topics.splice(index, 1);
        },
        removeFromSelectedTopics(topic, index) {
            this.form.selected_topics.splice(index, 1);
            this.selected_topics_set.delete(topic.id);
            this.filtered_topics.unshift(topic);
        },
        scaffholdingChange(language) {
            this.selectedLanguage = language;
            this.scaffholdingDropdown = false;
            this.syncAceEditor();
        }
    },
    computed: {
        difficulty() {
            switch (this.form.difficulty) {
                case 1: return 'Easy';
                case 2: return 'Medium';
                case 3: return 'Hard';
                default: {
                    this.form.difficulty = 1;
                    return 'Easy';
                }
            }
        },
    },
    props: {
        errors: null,
        prefilledForm: Object,
        url: String,
        method: String,
    },
    mounted() {
        // form
        if (this.$props.prefilledForm) {
            this.form = this.prefilledForm;
            const receivedScaffs = this.form.scaffholdings;
            this.form.scaffholdings = {}
            receivedScaffs.forEach(scaff => {
                this.form.scaffholdings[scaff.language_id] = scaff.scaffholding;
            });
        }

        // editor
        this.editor = ace.edit(this.$refs.aceEditor, {
            minLines: 10,
            fontSize: 12,
            showPrintMargin: false,
            theme: 'ace/theme/cloud_editor_dark',
            mode: 'ace/mode/javascript',
            keyboardHandler: 'ace/keyboard/vim',
            tabSize: 4
        });
        this.editor.setValue(this.form.scaffholding);
        this.editor.gotoLine(1);
    },
    watch: {
        errors: {
            handler(newValue, oldValue) {
                console.log(newValue);
            },
            deep: true,
        }
    }
}
</script>
