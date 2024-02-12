<script setup>
import ProblemSetLayout from '../Pages/Layouts/ProblemSetLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ studyPlans: Array })
</script>

<template>
    <Head title="Yeetcode - Problems"></Head>
    <ProblemSetLayout>
        <div class="flex">
            <!-- left panel -->
            <div class="flex flex-col w-3/4">
                <!-- study plan -->
                <div>

                </div>
                <!-- filters -->
                <div>

                </div>
                <!-- problem list -->
                <div>

                </div>
            </div>
            <!-- right panel -->
            <div class="flex flex-col grow">
                lol
            </div>
        </div>
    </ProblemSetLayout>
</template>
