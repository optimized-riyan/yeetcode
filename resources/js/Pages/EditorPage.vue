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
                <!-- editor -->
                <div class="h-2/3 bg-leetcode-green">
                    <div ref="aceEditor" class="h-full">console.log('Hello World!');</div>
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
                            </ul>
                            <!-- testcase content -->
                            <div>
                                <ul>
                                    <li v-for="(tcParam, index) in tcParameters" :key="index">
                                        <TestcaseParam :parameterName="tcParameters[index]" :parameterContent="testcaseArray[currentTestcase].testcase"></TestcaseParam>
                                    </li>
                                </ul>
                            </div>
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
                                        <TestcaseParam :parameterName="tcParameters[index]" :parameterContent="testcaseArray[currentTestcase].testcase"></TestcaseParam>
                                    </li>
                                    <li>
                                        <TestcaseParam parameterName="Your output" :parameterContent="testcaseOutputs[currentTestcase]"></TestcaseParam>
                                    </li>
                                    <li>
                                        <TestcaseParam parameterName="Expected output" :parameterContent="expectedOutputs[currentTestcase]"></TestcaseParam>
                                    </li>
                                </ul>
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
            testcaseOutputs: ['gay1', 'gay2', 'gay3', 'gay4'],
            expectedOutputs: ['straight1', 'straight2', 'straight3', 'straight4'],
            editor: null,
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
            this.currentTestcase = index
        },
        async runTrivial() {
            const data = await (await fetch(`${this.$inertia.page.props.onlineCompilerDomain}/pycompiler/runtrivial`, {
                method: "post",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    code: this.editor.getValue(),
                    testcases: this.testcaseArray.map(tc => tc.testcase),
                }),
            })).json();
            console.log(data);
        }
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
    props: {
        problem: Object,
        trivialTestcases: Object,
    },
};
</script>
