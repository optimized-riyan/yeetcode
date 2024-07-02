<template>
    <div class="flex flex-col w-screen h-screen bg-leetcode-background text-leetcode-text">
        <!-- entire top navbar -->
        <nav class="flex justify-between items-center w-full bg-leetcode-navbar">
            <div>
                <ul class="flex mx-4 gap-3">
                    <li>
                        <Link :href="route('problems.index')">
                            Problems
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('contactme')">
                            Contact Me
                        </Link>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="flex">
                    <li>
                        <Link :href="route('profile.edit')">
                            <img :src="avatarImage" alt="avatar" width="30" height="30" class="rounded-full mx-3 my-2" />
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- content -->
        <div class="w-3/4 mx-auto flex-grow">
            <slot></slot>
        </div>
    </div>
</template>
<script>
import Dropdown from '../Components/Dropdown.vue';
import { Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";

export default {
    components: {
        Dropdown,
        Link,
    },
    props: {
        avatarImage: String
    }
}
</script>
