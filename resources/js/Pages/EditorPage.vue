<template lang="">
    <!-- entire page -->
    <div class="flex flex-col h-screen bg-leetcode-background text-leetcode-text">
        <!-- titlebar -->
        <div class="h-10 mt-1 shrink-0 flex justify-between items-center">
            <div class="ml-2">
                <Link :href="route('problems.index')"><i class="fa-solid fa-less-than text-xs"></i> Problem List</Link>
            </div>
            <div class="flex gap-2">
                <SolidButton @click="runTrivial" class="hover:bg-leetcode-backgroundlighter bg-leetcode-backgroundlight">Run</SolidButton>
                <SolidButton @click="submitCode" class="hover:bg-leetcode-backgroundlighter bg-leetcode-backgroundlight text-leetcode-green-light">Submit</SolidButton>
            </div>
            <div>
                <Link :href="route('profile.edit')">
                    <img :src="user.avatarImage" alt="avatar" width="30" height="30" class="rounded-full m-2" />
                </Link>
            </div>
        </div>
        <!-- left and right panel -->
        <splitpanes>
            <!-- left panel -->
            <pane>
                <LeftPanel :problem="problem"></LeftPanel>
            </pane>
            <!-- right panel -->
            <pane>
                <splitpanes :horizontal="true">
                    <pane>
                        <div class="h-full bg-leetcode-background flex flex-col" ref="pEditorAndSettings">
                            <!-- editor settings -->
                            <div class="flex justify-between items-center">
                                <!-- language dropdown -->
                                <Dropdown :available-languages="availableLanguages" :default-language="selectedLanguage" @lang-change="(language) => languageChange(language)"/>
                                <!-- code reset button -->
                                <div class="flex">
                                    <button type="button" @click="resetCode"><i class="fa-solid fa-rotate-left hover:text-leetcode-green-light text-lg mx-4"></i></button>
                                </div>
                            </div>
                            <!-- editor -->
                            <div class="grow">
                                <div ref="aceEditor" class="h-full" @input="syncAceEditor"></div>
                            </div>
                        </div>
                    </pane>
                    <!-- console -->
                    <pane>
                        <div class="overflow-auto m-3">
                            <!-- panel change buttons -->
                            <div class="flex gap-2 mb-2">
                                <SlabButton @click="()=>this.consolePanel='testcases'" value="Testcases" :is-active="getIsActive('testcases')" />
                                <SlabButton @click="()=>this.consolePanel='results'" value="Results" :is-active="getIsActive('results')"/>
                                <SlabButton @click="changeConsoleToSubmissionsAndFetch" value="Submissions" :is-active="getIsActive('submissions')"/>
                            </div>
                            <hr class="border-leetcode-green">
                            <!-- panel contents -->
                            <div class="mt-1 whitespace-pre-wrap">
                                <!-- testcases tab -->
                                <div v-if="consolePanel == 'testcases'">
                                    <!-- testcases -->
                                    <ul class="flex mt-3 mb-2 gap-2">
                                        <li v-for="(testcase, index) in testcaseArray" :key="index" class="my-auto relative">
                                            <SlabButton @click="testcaseChange(index)" :value="`Testcase ${index+1}`" :is-active="index == currentTestcase"/>
                                            <div class="absolute -top-2.5 -right-1" @click="removeTestcase(index)">
                                                <i class="fa-solid fa-xmark text-leetcode-text"></i>
                                            </div>
                                        </li>
                                        <li class="my-auto px-2 py-1 bg-leetcode-backgroundlight rounded-full border-2 border-leetcode-text border-opacity-0 hover:border-opacity-80" @click="addTestcase">
                                            <button type="button"><i class="fa-solid fa-plus"></i></button>
                                        </li>
                                    </ul>
                                    <!-- testcase content -->
                                    <div>
                                        <textarea cols="30" rows="6" v-model="testcaseArray[currentTestcase].testcase" class="bg-leetcode-backgroundlight resize-none"></textarea>
                                    </div>
                                </div>
                                <!-- results tab -->
                                <div v-else-if="consolePanel == 'results'">
                                    <div v-if="runError">
                                        {{ runError }}
                                    </div>
                                    <div v-else-if="testcaseOutputs.length == 0" class="ml-2 mt-2">
                                        <OutputDisplay text-color="text-leetcode-red">
                                            You need to run the program at least once
                                        </OutputDisplay>
                                    </div>
                                    <div v-else>
                                        <!-- testcases -->
                                        <ul class="flex my-2 gap-2">
                                            <li v-for="(testcase, index) in testcaseArray" :key="index">
                                                <SlabButton @click="testcaseChange(index)" :value="`Testcase ${index+1}`" />
                                            </li>
                                        </ul>
                                        <!-- testcase content -->
                                        <div>
                                            <ul>
                                                <li>
                                                    <p>Your output:</p>
                                                    <div class="ml-2 mt-2">
                                                        <OutputDisplay text-color="text-leetcode-text">
                                                            {{ testcaseOutputs[currentTestcase] }}
                                                        </OutputDisplay>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- submissions tab -->
                                <div v-else>
                                    <div v-if="isSubmissionsFetched">
                                        <div v-if="fetchedSubmissions.length == 0">
                                            No submissions yet
                                        </div>
                                        <div v-else>
                                            <ul class="flex flex-col mt-3 gap-2">
                                                <li v-for="(submission, index) in fetchedSubmissions" :key="index">
                                                    <Submission :status="submission.status" />
                                                </li>
                                            </ul>
                                        </div>
                                </div>
                                    <div v-else>
                                        Submissions are being fetched
                                    </div>
                                </div>
                            </div>
                        </div>
                    </pane>
                </splitpanes>
            </pane>
        </splitpanes>
    </div>
</template>
<script>
import ace from 'ace-builds';
import 'ace-builds/esm-resolver';
import workerJavascriptUrl from "ace-builds/src-noconflict/worker-javascript?url";
import workerPhpUrl from "ace-builds/src-noconflict/worker-php?url";
import "ace-builds/src-noconflict/theme-chaos";
import 'ace-builds/src-noconflict/mode-javascript';
import 'ace-builds/src-noconflict/mode-python';
import "ace-builds/src-noconflict/mode-c_cpp";
import "ace-builds/src-noconflict/mode-php";
import LeftPanel from "@/Pages/Components/EditorComponents/LeftPanel.vue";
import TestcaseParam from '@/Pages/Components/EditorComponents/TestcaseParam.vue';
import Submission from './Components/EditorComponents/Submission.vue';
import SlabButton from '@/Pages/Components/EditorComponents/SlabButton.vue';
import SolidButton from "@/Pages/Components/EditorComponents/SolidButton.vue";
import Dropdown from '@/Pages/Components/Dropdown.vue';
import OutputDisplay from './Components/EditorComponents/OutputDisplay.vue';
import { Splitpanes, Pane } from 'splitpanes';
import 'splitpanes/dist/splitpanes.css'
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

ace.config.setModuleUrl('ace/mode/javascript_worker', workerJavascriptUrl);
ace.config.setModuleUrl("ace/mode/php_worker", workerPhpUrl);

export default {
    data() {
        return {
            currentTestcase: 0,
            testcaseArray: Object.values(this.problem.testcases),
            tcParameters: this.problem.tc_parameters.split(' '),
            consolePanel: 'testcases', // testcases, results, submissions
            testcaseOutputs: [],
            expectedOutputs: [],
            editor: null,
            runError: null,
            availableLanguages: ['python', 'js', 'php', 'c', 'c++'],
            selectedLanguage: 'python',
            languageIds: {
                'python': 71,
                'js': 63,
                'php': 68,
                'c': 50,
                'c++': 54,
            },
            editorModes: {
                "python": "python",
                "js": "javascript",
                "php": "php",
                "c": "c_cpp",
                "c++": "c_cpp",
            },
            originalScaffholdings: null,
            fetchedSubmissions: null,
            isSubmissionsFetched: false,
            userId: 0,
        }
    },
    components: {
        LeftPanel,
        TestcaseParam,
        Link,
        Submission,
        SlabButton,
        SolidButton,
        Dropdown,
        OutputDisplay,
        Pane,
        Splitpanes,
    },
    computed: {
    },
    methods: {
        testcaseChange(index) {
            this.currentTestcase = index;
        },
        async runTrivial() {
            this.runError = "";
            this.consolePanel = "testcases";

            const submissions = [];
            for (let i = 0; i < this.testcaseArray.length; i++) {
                submissions.push({
                    source_code: btoa(this.editor.getValue()),
                    stdin: btoa(this.testcaseArray[i].testcase),
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
                let response = await axios.post("/api/runTrivial", data, config);

                if (response.data.error)
                    this.runError = this.base64decode(response.data.error);
                else {
                    this.testcaseOutputs = response.data.outputs.map(output => this.base64decode(output));
                }
            }
            catch (err) {
                console.error(err);
            }
            finally {
                this.consolePanel = "results";
            }
        },
        async submitCode() {
            this.isSubmissionsFetched = false;
            this.consolePanel = "submissions";
            const submissionCreationUrl = "/api/submitCode";

            const config = {
                headers: {
                    'Content-Type': 'application/json',
                },
            };
            const data = {
                code: this.editor.getValue(),
                problem_id: this.problem.id,
                user_id: this.userId,
                language_id: this.languageIds[this.selectedLanguage],
            };

            try {
                await axios.post(submissionCreationUrl, data, config);
                this.changeConsoleToSubmissionsAndFetch();
            }
            catch (err) {
                console.error(err);
            }
        },
        addTestcase() {
            this.testcaseArray.push({ testcase: '' });
            if (this.testcaseArray.length > 1) {
                this.testcaseArray.at(-1).testcase = this.testcaseArray.at(-2).testcase;
            }
            this.currentTestcase = this.testcaseArray.length - 1;
        },
        removeTestcase(index) {
            if (this.testcaseArray.length <= 1) return;
            this.testcaseArray.splice(index, 1);
            this.currentTestcase = this.testcaseArray.length-1;
        },
        languageChange(language) {
            const modeUrl = "ace/mode/";
            this.selectedLanguage = language;
            this.editor.session.setMode(modeUrl + this.editorModes[language]);
            this.changeEditorValueNoSelection(this.scaffholdings[this.languageIds[this.selectedLanguage]]);
            localStorage.setItem(`[${this.problem.id},selectedLanguage]`, this.selectedLanguage);
        },
        syncAceEditor() {
            let languageId = this.languageIds[this.selectedLanguage];
            if (this.scaffholdings[languageId] === undefined) this.scaffholdings[languageId] = "";
            this.scaffholdings[languageId] = this.editor.getValue();
        },
        storeCode() {
            localStorage.setItem([this.languageIds[this.selectedLanguage], this.problem.id], this.editor.getValue());
        },
        retrieveCode(language) {
            return localStorage.getItem([this.languageIds[language], this.problem.id]);
        },
        resetCode() {
            this.scaffholdings[this.languageIds[this.selectedLanguage]] = this.originalScaffholdings[this.languageIds[this.selectedLanguage]];
            this.changeEditorValueNoSelection(this.scaffholdings[this.languageIds[this.selectedLanguage]]);
            this.storeCode();
        },
        changeEditorValueNoSelection(value) {
            this.editor.setValue(value);
            this.editor.clearSelection();
        },
        base64decode(base64str) {
            // Decode base64str to binary string
            const binaryString = atob(base64str);

            // Convert binary string to a Uint8Array
            const binaryArray = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                binaryArray[i] = binaryString.charCodeAt(i);
            }

            // Convert Uint8Array to a UTF-8 string
            const decoder = new TextDecoder('utf-8');
            return decoder.decode(binaryArray);
        },
        async changeConsoleToSubmissionsAndFetch() {
            this.consolePanel = "submissions";
            if (this.isSubmissionsFetched) return;

            const getUrl = `/api/getSubmissions/${this.problem.id}/${this.userId}`;
            const res = await axios.get(getUrl);
            this.fetchedSubmissions = res.data;
            this.isSubmissionsFetched = true;
        },
        getIsActive(consolePanel) {
            return consolePanel == this.consolePanel;
        },
    },
    mounted() {
        this.originalScaffholdings = JSON.parse(JSON.stringify(this.scaffholdings));

        this.availableLanguages.forEach(language => {
            const code = this.retrieveCode(language);
            if (code !== null)
                this.scaffholdings[this.languageIds[language]] = code;
        });

        // local storage timer
        setInterval(this.storeCode, 150);

        this.editor = ace.edit(this.$refs.aceEditor, {
            minLines: 10,
            fontSize: 14,
            showPrintMargin: false,
            theme: 'ace/theme/chaos',
            mode: 'ace/mode/python',
            tabSize: 4
        });
        this.changeEditorValueNoSelection(this.scaffholdings[this.languageIds[this.selectedLanguage]]);

        const storedLanguage = localStorage.getItem(`[${this.problem.id},selectedLanguage]`);
        if (storedLanguage) {
            this.languageChange(storedLanguage);
        }
    },
    created() {
        this.userId = this.user.id;
    },
    props: {
        problem: Object,
        scaffholdings: Object,
        user: Object,
    },
};
</script>
<style>
.splitpanes--vertical > .splitpanes__splitter {
  min-width: 6px;
  background: linear-gradient(90deg, #ccc, #111);
}

.splitpanes--horizontal > .splitpanes__splitter {
  min-height: 6px;
  background: linear-gradient(0deg, #ccc, #111);
}
</style>
