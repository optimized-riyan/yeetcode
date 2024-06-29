<template lang="">
    <p :class="`font-bold text-lg ${messageColor} bg-leetcode-backgroundlight px-3 py-2 rounded-lg`">{{ message }}</p>
</template>
<script>
export default {
    data() {
        return {
            messageColor: "text-red-600",
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
    }
}
</script>
