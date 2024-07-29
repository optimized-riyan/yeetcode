<template lang="">
    <div :class="`font-bold bg-leetcode-backgroundlight px-3 py-2 rounded-lg justify-between flex`">
        <div :class="`text-lg ${messageColor}`">{{ message }}</div>
        <div v-if="submission.errorneous_tc">
            <button type="button" @click="showTestcase" class="text-sm">Open Testcase</button>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            messageColor: "text-red-600",
            status: this.submission.status,
        }
    },
    props: {
        submission: Object,
    },
    computed: {
        message() {
            let res = "";
            switch (this.status) {
                case "wrong":
                    res = "Wrong Answer";
                    break;
                case "error":
                    res = "Error";
                    break;
                case "tle":
                    res = "Time Limit Exceeded";
                    break;
                case "right":
                    res = "Accepted";
                    this.messageColor = "text-leetcode-green-light";
                    break;
                default:
                    console.error("Invalid submission status");
                    break;
            }
            return res;
        },
    },
    methods: {
        showTestcase() {
            window.open(`/api/getErrorTc/${this.submission.id}`);
        }
    }
}
</script>
