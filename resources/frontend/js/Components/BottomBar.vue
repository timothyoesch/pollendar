<script setup>
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

let currentRoute = ref(window.location.pathname);

</script>
<template>
    <div class="pl-app__bottom-bar w-full bg-primary-400 fixed bottom-0 left-0 py-4">
        <div class="pl-app__container px-0!">
            <div class="flex justify-between items-center">
                <div class="pl-app__bottom-bar__icon w-full flex justify-center">
                    <Link href="/app">
                        <Icon
                            icon="heroicons:home-20-solid"
                            class="w-10 h-10"
                            :class="currentRoute === '/app' ? 'text-secondary-200' : 'text-white'"
                        />
                    </Link>
                </div>
                <div class="pl-app__bottom-bar__fab">
                    <Link href="/app/new" class="w-18 h-18 mt-[-75%] bg-white rounded-full flex items-center justify-center">
                        <Icon icon="heroicons:plus-circle-16-solid" class="w-24 h-24 text-secondary-400" />
                    </Link>
                </div>
                <div class="pl-app__bottom-bar__icon w-full flex justify-center">
                    <Link href="/app/profile">
                        <Icon icon="heroicons:user-circle-20-solid" class="w-10 h-10" :class="currentRoute === '/app/profile' ? 'text-secondary-200' : 'text-white'" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.pl-app__bottom-bar {
    filter: drop-shadow(0px -2px 4px rgba(0, 0, 0, 0.1));
}
</style>
