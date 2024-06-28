<template>
    <Head title="Problems"></Head>
    <ProblemSetLayout :avatarImage="avatarImage">
        <div class="flex">
            <!-- left panel -->
            <div class="flex flex-col w-3/4">
                <!-- problem list -->
                <div class="flex flex-col my-1">
                    <ul v-for="problem in problemList.data" :key="problem.id">
                        <ProblemEntry :problem="problem"></ProblemEntry>
                    </ul>
                </div>
                <!-- filters -->
                <div>

                </div>
                <div>
                    <Paginator :links="problemList.links"></Paginator>
                </div>
            </div>
            <!-- right panel -->
            <div class="flex flex-col grow">
                lol
            </div>
        </div>
    </ProblemSetLayout>
</template>
<script>
import ProblemSetLayout from '@/Pages/Layouts/ProblemSetLayout.vue';
import ProblemEntry from '@/Pages/Components/ProblemEntry.vue';
import Paginator from '@/Pages/Components/ProblemSetComponents/Paginator.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        ProblemEntry,
        ProblemSetLayout,
        Paginator,
        Head,
    },
    props: {
        problemList: Object,
        avatarImage: String,
    },
}
</script>
