<template lang="">
    <!-- entire page -->
    <div class="flex flex-col h-screen">
        <!-- titlebar -->
        <div class="bg-leetcode-background text-leetcode-text h-10 shrink-0 flex justify-between">
            <div>
                <Link :href="route('problems.index')">Problem List</Link>
            </div>
            <div>
                <button type="button" @click="runTrivial" class="px-2">Run</button>
                <button type="button" @click="submitCode">Submit</button>
            </div>
            <div>
                Profile
            </div>
        </div>
        <!-- left and right panel -->
        <div class="flex grow overflow-auto">
            <!-- left panel -->
            <div class="w-1/3" ref="pLeftPanel">
                <LeftPanel :problem="problem"></LeftPanel>
            </div>
            <!-- gutter b/w left & right panels -->
            <div ref="pGutterLR" class="h-full w-2 top-0 left-0 cursor-col-resize bg-leetcode-backgroundlighter"></div>
            <!-- right panel -->
            <div class="flex flex-col grow">
                <div class="h-2/3 bg-leetcode-green flex flex-col" ref="pEditorAndSettings">
                    <!-- editor settings -->
                    <div class="h-6 flex justify-between">
                        <!-- language dropdown -->
                        <div class="relative flex">
                            <button type="button" @click="languageDropdown = !languageDropdown">{{ selectedLanguage }}</button>
                            <div class="absolute top-6 z-10 bg-leetcode-background text-leetcode-text" v-if="languageDropdown">
                                <ul v-for="(language, index) in availableLanguages" :key="index">
                                    <li @click="languageChange(language)" class="cursor-pointer">{{ language }}</li>
                                </ul>
                            </div>
                        </div>
                        <!-- code reset button -->
                        <div class="flex">
                            <button type="button" @click="resetCode">Reset</button>
                        </div>
                    </div>
                    <!-- editor -->
                    <div class="grow">
                        <div ref="aceEditor" class="h-full" @input="syncAceEditor"></div>
                    </div>
                </div>
                <!-- gutter b/w editor+settings & console -->
                <div ref="pGutterEC" class="w-full h-2 top-0 left-0 cursor-row-resize bg-leetcode-backgroundlighter"></div>
                <!-- console -->
                <div class="grow">
                    <!-- panel change buttons -->
                    <div>
                        <button type="button" @click="()=>this.consolePanel='testcases'">Testcases</button>
                        <button type="button" @click="()=>this.consolePanel='results'">Test Results</button>
                        <button type="button" @click="changeConsoleToSubmissionsAndFetch">Submissions</button>
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
                                <textarea cols="30" rows="7" v-model="testcaseArray[currentTestcase].testcase"></textarea>
                            </div>
                        </div>
                        <div v-else-if="consolePanel == 'results'">
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
                                        <li>
                                            <p>Your output:</p>index
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div v-if="isSubmissionsFetched">
                                <ul v-for="(submission, index) in fetchedSubmissions" :key="index">
                                    <Submission :title="getSubmissionStatusString(submission.status)" />
                                </ul>
                            </div>
                            <div v-else>
                                Submissions are being fetched
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
import workerPhpUrl from "ace-builds/src-noconflict/worker-php?url";
import 'ace-builds/src-noconflict/theme-cloud_editor_dark';
import 'ace-builds/src-noconflict/mode-javascript';
import 'ace-builds/src-noconflict/mode-python';
import "ace-builds/src-noconflict/mode-c_cpp";
import "ace-builds/src-noconflict/mode-php";
import 'ace-builds/src-noconflict/keybinding-vim';
import LeftPanel from "@/Pages/Components/EditorComponents/LeftPanel.vue";
import TestcaseParam from '@/Pages/Components/EditorComponents/TestcaseParam.vue';
import Submission from './Components/EditorComponents/Submission.vue';
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
        }
    },
    components: {
        LeftPanel,
        TestcaseParam,
        Link,
        Submission,
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
            const postUrl = `http://${this.$inertia.page.props.judge0Domain}/submissions/batch?base64_encoded=true`;

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
                let response = await axios.post(postUrl, data, config);

                let tokens = response.data.map(token => token.token);
                let getUrl = (token) => `http://${this.$inertia.page.props.judge0Domain}/submissions/${token}?`;

                this.testcaseOutputs = [];
                for (let i = 0; i < tokens.length; i++) {
                    do {
                        response = await axios.get(getUrl(tokens[i]) + "fields=status_id");
                    } while (response.data.status_id == 2 || response.data.status_id == 1)
                    response = await axios.get(getUrl(tokens[i]) + "base64_encoded=true");
                    console.log(response);
                    if (response.data.stderr || response.data.compile_output) {
                        if (response.data.stderr)
                            this.runError = this.base64decode(response.data.stderr);
                        else
                            this.runError = this.base64decode(response.data.compile_output);
                        break;
                    }
                    else {
                        this.testcaseOutputs.push(this.base64decode(response.data.stdout));
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
        languageChange(language) {
            const modeUrl = "ace/mode/";
            this.selectedLanguage = language;
            this.editor.session.setMode(modeUrl + this.editorModes[language]);
            this.languageDropdown = false;
            this.changeEditorValueNoSelection(this.scaffholdings[this.languageIds[this.selectedLanguage]]);
            localStorage.setItem(`[${this.problem.id},selectedLanguage]`, this.selectedLanguage);
        },
        syncAceEditor() {
            let languageId = this.languageIds[this.selectedLanguage];
            if (this.scaffholdings[languageId] === undefined) this.scaffholdings[languageId] = "";
            this.scaffholdings[languageId] = this.editor.getValue();
        },
        resizerWidth(e, leftPane) {
            window.addEventListener('mousemove', mousemove);
            window.addEventListener('mouseup', mouseup);

            let prevX = e.x;
            const leftPanel = leftPane.getBoundingClientRect();

            function mousemove(e) {
                let newX = prevX - e.x;
                leftPane.style.width = leftPanel.width - newX + "px";
            }

            function mouseup() {
                window.removeEventListener('mousemove', mousemove);
                window.removeEventListener('mouseup', mouseup);
            }
        },
        resizerHeight(e, upperPane) {
            window.addEventListener('mousemove', mousemove);
            window.addEventListener('mouseup', mouseup);

            let prevY = e.y;
            const upperPanel = upperPane.getBoundingClientRect();

            function mousemove(e) {
                let newY = prevY - e.y;
                upperPane.style.height = upperPanel.height - newY + "px";
            }

            function mouseup() {
                window.removeEventListener('mousemove', mousemove);
                window.removeEventListener('mouseup', mouseup);
            }
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
        getSubmissionStatusString(status) {
            let res = "";
            switch (status) {
                case "wrong":
                    res = "Wrong Answer";
                    break;
                case "error":
                    res = "Error";
                    break;
                case "right":
                    res = "Accepted";
                    break;
                case "tle":
                    res = "Time Limit Exceeded";
                    break;
                default:
                    console.error("Invalid submission status");
                    break;
            }
            return res;
        },
    },
    mounted() {
        this.originalScaffholdings = JSON.parse(JSON.stringify(this.scaffholdings));

        this.availableLanguages.forEach(language => {
            const code = this.retrieveCode(language);
            if (code !== undefined) this.scaffholdings[this.languageIds[language]] = code;
        });

        // local storage timer
        setInterval(this.storeCode, 100);

        this.editor = ace.edit(this.$refs.aceEditor, {
            minLines: 10,
            fontSize: 14,
            showPrintMargin: false,
            theme: 'ace/theme/cloud_editor_dark',
            mode: 'ace/mode/python',
            keyboardHandler: 'ace/keyboard/vim',
            tabSize: 4
        });
        this.changeEditorValueNoSelection(this.scaffholdings[this.languageIds[this.selectedLanguage]]);

        const storedLanguage = localStorage.getItem(`[${this.problem.id},selectedLanguage]`);
        if (storedLanguage) {
            this.languageChange(storedLanguage);
        }

        // resizers
        this.$refs.pGutterLR.addEventListener('mousedown', e => this.resizerWidth(e, this.$refs.pLeftPanel));
        this.$refs.pGutterEC.addEventListener('mousedown', e => this.resizerHeight(e, this.$refs.pEditorAndSettings));
    },
    props: {
        problem: Object,
        scaffholdings: Object,
        userId: Number,
    },
};
</script>
