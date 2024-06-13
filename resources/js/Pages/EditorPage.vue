<template lang="">
    <!-- entire page -->
    <div class="flex flex-col h-screen">
        <!-- titlebar -->
        <div class="bg-leetcode-background text-leetcode-text h-10 shrink-0 flex justify-between">
            <div>
                <Link :href="route('problems.index')">Problem List</Link>
            </div>
            <div>
                <button type="button" @click="runTrivial">Run</button>
            </div>
            <div>
                Profile
            </div>
        </div>
        <!-- left and right panel -->
        <div class="flex grow overflow-auto">
            <!-- left panel -->
            <Description :problem="problem"></Description>
            <!-- right panel -->
            <div class="flex flex-col grow">
                <div class="h-2/3 bg-leetcode-green flex flex-col">
                    <!-- editor settings -->
                    <div class="h-6">
                        <!-- language dropdown -->
                        <div class="relative">
                            <button type="button" @click="languageDropdown = !languageDropdown">{{ selectedLanguage }}</button>
                            <div class="absolute top-6 z-10 bg-leetcode-background text-leetcode-text" v-if="languageDropdown">
                                <ul v-for="(language, index) in availableLanguages" :key="index">
                                    <li @click="()=>{selectedLanguage = language; languageDropdown = false;}" class="cursor-pointer">{{ language }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- editor -->
                    <div class="grow">
                        <div ref="aceEditor" class="h-full"></div>
                    </div>
                </div>
                <!-- console -->
                <div class="grow">
                    <!-- panel change buttons -->
                    <div>
                        <button type="button" @click="()=>this.consolePanel='testcases'">Testcases</button>
                        <button type="button" @click="()=>this.consolePanel='results'">Test Results</button>
                    </div>
                    <!-- panel contents -->
                    <div>
                        <div v-if="consolePanel == 'testcases'">
                            <!-- testcases -->
                            <ul class="flex">
                                <li v-for="(testcase, index) in testcaseArray" :key="index">
                                    <button type="button" @click="testcaseChange(index)">Testcase {{index+1}}</button>
                                </li>
                                <li>
                                    <!-- <button type="button" @click="testcaseArray.push({testcase: ''})">Add</button> -->
                                    <button type="button" @click="addTestcase">Add</button>
                                </li>
                            </ul>
                            <!-- testcase content -->
                            <div>
                                <ul>
                                    <li v-for="(tcParam, index) in tcParameters" :key="index">
                                        <TestcaseParam :currentTestcase="currentTestcase" :testcaseArray="testcaseArray" @sync="(value)=>{this.testcaseArray[currentTestcase].testcase = value}"></TestcaseParam>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="runError">
                                {{ runError }}
                            </div>
                            <div v-else-if="testcaseOutputs.length == 0">
                                You need to run the program at least once
                            </div>
                            <div v-else>
                                <!-- testcases -->
                                <ul class="flex">
                                    <li v-for="(testcase, index) in testcaseArray" :key="index">
                                        <button type="button" @click="testcaseChange(index)">Testcase {{index+1}}</button>
                                    </li>
                                </ul>
                                <!-- testcase content -->
                                <div>
                                    <ul>
                                        <li v-for="(tcParam, index) in tcParameters" :key="index">
                                            <TestcaseParam :currentTestcase="currentTestcase" :testcaseArray="testcaseArray"></TestcaseParam>
                                        </li>
                                        <li>
                                            <p>Your output:</p>
                                            {{ testcaseOutputs[currentTestcase] }}
                                        </li>
                                        <li>
                                            <p>Expected Output:</p>
                                            {{ expectedOutputs[currentTestcase] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ace from 'ace-builds';
import 'ace-builds/esm-resolver';
import workerJavascriptUrl from "ace-builds/src-noconflict/worker-javascript?url";
import 'ace-builds/src-noconflict/theme-cloud_editor_dark';
import 'ace-builds/src-noconflict/mode-javascript';
import 'ace-builds/src-noconflict/mode-python';
import 'ace-builds/src-noconflict/keybinding-vim';
import Description from '@/Pages/Components/EditorComponents/Description.vue';
import TestcaseParam from '@/Pages/Components/EditorComponents/TestcaseParam.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

ace.config.setModuleUrl('ace/mode/javascript_worker', workerJavascriptUrl);

export default {
    data() {
        return {
            currentTestcase: 0,
            testcaseArray: Object.values(this.problem.testcases),
            tcParameters: this.problem.tc_parameters.split(' '),
            consolePanel: 'testcases',
            testcaseOutputs: [],
            expectedOutputs: [],
            editor: null,
            runError: null,
            languageDropdown: false,
            availableLanguages: ['python', 'js', 'php', 'c', 'c++'],
            selectedLanguage: 'python',
            languageIds: {
                'python': 71,
                'js': 63,
                'php': 68,
                'c': 50,
                'c++': 54,
            },
        }
    },
    components: {
        Description,
        TestcaseParam,
        Link,
    },
    computed: {
    },
    methods: {
        testcaseChange(index) {
            this.currentTestcase = index;
        },
        async runTrivial() {
            const postUrl = `http://${this.$inertia.page.props.judge0Domain}/submissions/batch`;

            const submissions = [];
            for (let i = 0; i < this.testcaseArray.length; i++) {
                submissions.push({
                    source_code: this.editor.getValue(),
                    stdin: this.testcaseArray[i].testcase,
                    language_id: this.languageIds[this.selectedLanguage],
                });
            }

            const data = { submissions };
            const config = {
                headers: {
                    'Content-Type': 'application/json',
                },
            };

            try {
                let response = await axios.post(postUrl, data, config);

                let tokens = response.data.map(token => token.token);
                let getUrl = (token) => `http://${this.$inertia.page.props.judge0Domain}/submissions/${token}`;


                this.testcaseOutputs = [];
                for (let i = 0; i < tokens.length; i++) {
                    do {
                        response = await axios.get(getUrl(tokens[i]) + '?fields=status_id');
                    } while (response.data.status_id == 2 || response.data.status_id == 1)
                    response = await axios.get(getUrl(tokens[i]));
                    if (response.data.stderr) {
                        this.runError = response.data.stderr;
                        break
                    }
                    else {
                        this.testcaseOutputs.push(response.data.stdout);
                    }
                }
            }
            catch (err) {
                console.error(err);
            }
            finally {
                this.consolePanel = 'results';
            }
        },
        addTestcase() {
            this.testcaseArray.push({testcase: ''});
            if (this.testcaseArray.length > 1) {
                this.testcaseArray.at(-1).testcase = this.testcaseArray.at(-2).testcase;
            }
            this.currentTestcase = this.testcaseArray.length - 1;
        }
    },
    mounted() {
        this.editor = ace.edit(this.$refs.aceEditor, {
            minLines: 10,
            fontSize: 12,
            showPrintMargin: false,
            theme: 'ace/theme/cloud_editor_dark',
            mode: 'ace/mode/python',
            keyboardHandler: 'ace/keyboard/vim',
            tabSize: 4
        });
        this.editor.setValue(this.problem.scaffholding);
    },
    props: {
        problem: Object,
        trivialTestcases: Object,
    },
};
</script>
