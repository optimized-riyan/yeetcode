<script setup>
import ProblemSetLayout from '../Pages/Layouts/ProblemSetLayout.vue';
import ProblemEntry from './Components/ProblemEntry.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ studyPlans: Array, problemList: Array })
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
                <div class="flex flex-col" v-for="problem in problemList" :key="problem.id">
                    <ProblemEntry :problem="problem"></ProblemEntry>
                </div>
            </div>
            <!-- right panel -->
            <div class="flex flex-col grow">
                lol
            </div>
        </div>
    </ProblemSetLayout>
</template>
