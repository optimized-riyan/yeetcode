<script setup>
import Layout from '../Layouts/ProblemSetLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({ user: Object })
</script>

<template>
    <Layout>
        <Head title="Yeetcode - Problems" />
        <p>Hello {{ user.name }}, welcome to your first Inertia app!</p>
    </Layout>
</template>
