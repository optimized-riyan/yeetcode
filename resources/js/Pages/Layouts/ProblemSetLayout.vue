<template>
    <div class="flex flex-col w-screen min-h-screen bg-leetcode-background text-leetcode-text">
        <!-- entire top navbar -->
        <nav class="flex justify-between w-3/4 mx-auto bg-leetcode-navbar">
            <div>
                <ul class="flex">
                    <li>Icon</li>
                    <li>Explore</li>
                    <li>Problems</li>
                    <li>Content</li>
                    <li>Discuss</li>
                    <li>
                        <Dropdown>Interview</Dropdown>
                    </li>
                    <li>
                        <Dropdown>Store</Dropdown>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="flex">
                    <li>Profilepic</li>
                    <li>Notification</li>
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

export default {
    components: {
        Dropdown,
    }
}
</script>
