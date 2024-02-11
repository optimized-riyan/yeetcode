<script setup>
import Dropdown from '../Components/Dropdown.vue';
</script>

<template>
    <div class="flex flex-col w-screen">
        <!-- entire top navbar -->
        <nav class="flex justify-between w-3/4 mx-auto">
            <div>
                <ul class="flex">
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
        <div class="w-3/4 mx-auto">
            <slot></slot>
        </div>
    </div>
</template>
