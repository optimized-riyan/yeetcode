<template lang="">
    <Head title="Contact Me" />
    <ProblemSetLayout :avatar-image="avatarImage">
        <div class="flex justify-center items-center h-full w-full">
            <div class="h-2/6 w-4/12 bg-leetcode-backgroundlight rounded-lg flex flex-col justify-center">
                <ul class="ml-32 text-leetcode-text">
                    <li>
                        <i class="fa-brands fa-linkedin pr-2 text-blue-700"></i> <a href="https://www.linkedin.com/in/riyangonsalves/">riyangonsalves</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone pr-2 text-green-500"></i> +91-9325310782
                    </li>
                    <li>
                        <i class="fa-regular fa-envelope pr-2 text-red-500"></i> <a href="mailto:riyan0350@gmail.com">riyan0350@gmail.com</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-github-alt pr-2"></i> <a href="https://github.com/optimized-riyan">optimized-riyan</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-instagram pr-2 text-pink-500"></i> riyangonsalves_
                    </li>
                </ul>
            </div>
        </div>
    </ProblemSetLayout>
</template>
<script>
import ProblemSetLayout from './Layouts/ProblemSetLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        ProblemSetLayout,
        Head,
    },
    props: {
        avatarImage: String,
    },
}
</script>
