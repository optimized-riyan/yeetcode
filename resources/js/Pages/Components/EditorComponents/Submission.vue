<template lang="">
    <p :class="`font-bold text-lg ${messageColor} bg-leetcode-backgroundlight px-3 py-2 rounded-lg`">{{ message }}</p>
</template>
<script>
export default {
    data() {
        return {
            messageColor: "text-white",
        }
    },
    props: {
        status: String,
    },
    computed: {
        message() {
            let res = "";
            switch (this.status) {
                case "wrong":
                    res = "Wrong Answer";
                    this.messageColor = "text-red-600";
                    break;
                case "error":
                    res = "Error";
                    this.messageColor = "text-red-600";
                    break;
                case "tle":
                    res = "Time Limit Exceeded";
                    this.messageColor = "text-red-600";
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
    }
}
</script>
