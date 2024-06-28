<template>
    <div class="flex flex-col w-screen min-h-screen bg-leetcode-background text-leetcode-text">
        <!-- entire top navbar -->
        <nav class="flex justify-between w-full mx-auto bg-leetcode-navbar">
            <div>
                <ul class="flex">
                    <li>Problems</li>
                    <li>About</li>
                </ul>
            </div>
            <div>
                <ul class="flex">
                    <li>
                        <Link :href="route('profile.edit')">
                            <img :src="avatarImage" alt="avatar" width="30" height="30" class="rounded-lg" />
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- content -->
        <div class="w-3/4 mx-auto">
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
