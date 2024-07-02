<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key"
                    class="mr-1 px-4 py-2 text-sm leading-4 text-leetcode-text rounded flex items-center" v-html="link.label" />
                <Link v-else
                    class="mr-1 px-3 py-2 text-sm leading-4 rounded hover:bg-leetcode-backgroundlighter flex items-center"
                    :class="{ 'bg-leetcode-backgroundlight': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    props: {
        links: Array,
    },
    components: {
        Link
    }
}
</script>
