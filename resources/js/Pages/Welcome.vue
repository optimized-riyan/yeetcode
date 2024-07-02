<template lang="">
    <div class="h-screen w-screen bg-leetcode-background flex flex-col justify-center items-center text-leetcode-text">
        <div class="h-2/6 w-4/12 bg-leetcode-backgroundlight flex flex-col px-8 py-6 justify-between rounded-xl">
            <div class="flex flex-col items-center">
                <div class="self-center font-bold text-4xl">Welcome to YeetCode!</div>
                <div class="mt-12">An online coding platform made with <span class="font-semibold">emoji barrage</span> using Laravel, Vue and MySQL and more technologies by yours truly, Riyan Gonsalves!</div>
            </div>
            <div class="flex justify-between">
                <div class="flex gap-8">
                    <Link>
                        <u>Login</u>
                    </Link>
                    <Link>
                        <u>Register</u>
                    </Link>
                </div>
                <div>
                    <Link>
                        <u>Go to problems page</u>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    }
}
</script>
