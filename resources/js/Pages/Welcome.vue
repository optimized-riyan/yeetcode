<template lang="">
    <div class="h-screen w-screen bg-leetcode-background flex flex-col justify-center items-center text-leetcode-text">
        <div class="h-1/4 w-4/12 bg-leetcode-backgroundlight flex flex-col px-8 py-6 justify-between rounded-xl">
            <div class="flex flex-col items-center">
                <div class="self-center font-bold text-4xl">Welcome to <span class="text-leetcode-yellow">YeetCode</span>!</div>
                <div class="mt-4">
                    An online coding platform made with
                    <span>
                        <i class="fa-solid fa-heart text-leetcode-red"></i>
                    </span>
                     using Laravel, Vue and MySQL and more technologies by yours truly, optimized-riyan!
                </div>
            </div>
            <div class="flex justify-between text-leetcode-blue">
                <div>
                    <Link :href="route('problems.index')">
                        <u>Go to problems page</u>
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <!-- version number -->
    <div class="absolute bottom-0 text-leetcode-textdark text-lg px-2 py-1">
        {{ appVersionNumber }}
    </div>
</template>
<script>
import { Link } from "@inertiajs/vue3";

export default {
    data() {
        return {
            emojiIndex: 0,
            emojis: [
                `<i class="fa-solid fa-heart"></i>`,
                `<i class="fa-regular fa-face-laugh-squint"></i>`,
                `<i class="fa-solid fa-face-grin-tears"></i>`,
                `<i class="fa-regular fa-face-sad-cry"></i>`,
            ],
        }
    },
    components: {
        Link,
    },
    methods: {
        nextEmoji() {
            this.emojiIndex = (this.emojiIndex + 1) % this.emojis.length;
        }
    },
    props: {
        appVersionNumber: String,
    }
}
</script>
