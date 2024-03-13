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
                        <div ref="aceEditor" class="h-full">print('Hello '+input())</div>
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
                                    <button type="button" @click="testcaseArray.push({testcase: ''})">Add</button>
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
import Description from './Components/EditorComponents/Description.vue';
import TestcaseParam from './Components/EditorComponents/TestcaseParam.vue';
import { Link } from '@inertiajs/vue3';

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
            availableLanguages: [ 'python', 'js', 'php', 'c', 'c++', 'java' ],
            selectedLanguage: 'python',
            languageUrls: {
                'python': 'pycompiler',
                'js': 'jscompiler',
                'php': 'phpcompiler',
                'c': 'ccompiler',
                'c++': 'cppcompiler',
                'java': 'javacompiler',
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
            const data = await (await fetch(`${this.$inertia.page.props.onlineCompilerDomain}/${this.languageUrls[this.selectedLanguage]}/runtrivial`, {
                method: "post",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    code: this.editor.getValue(),
                    testcases: this.testcaseArray.map(tc => tc.testcase),
                }),
            })).json();
            this.consolePanel = 'results';
            if (data.error) {
                this.runError = data.error;
            }
            else {
                this.testcaseOutputs = data.outputs;
            }
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
    },
    props: {
        problem: Object,
        trivialTestcases: Object,
    },
};
</script>
