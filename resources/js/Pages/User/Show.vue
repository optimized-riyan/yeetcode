<template>
    <Layout>
        <Head title="Yeetcode - Problems" />
        <p>Hello {{ user.name }}, welcome to your first Inertia app!</p>
    </Layout>
</template>
<script>
import Layout from '../Layouts/ProblemSetLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
    components: {
        Layout,
        Head,
    },
    props: {
        user: Object,
    }
}
</script>
