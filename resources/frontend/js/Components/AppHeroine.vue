<script setup>
import { Icon } from '@iconify/vue';
</script>
<template>
    <div class="pl-app__heroine w-full pt-4">
        <div class="pl-app__container text-white">
            <p class="flex items-center justify-center text-center">
                Today’s pollen levels:
                <Icon icon="heroicons:map-pin-20-solid" class="ml-1 w-5 h-5" />
                {{ $page.props.auth.user.homebase_name }}
            </p>
            <div class="pl-app__heroine__pollen-levels bg-secondary-50 p-6 rounded-lg text-black mt-2 shadow-md">
                <div class="pl-app__heroine__pollen-levels__items flex justify-between">
                    <div class="pl-app__heroine__pollen-levels__items__item">
                        <div class="pl-app__heroine__pollen-levels__items__item__box bg-signal-verylow text-white">
                            <span class="fugaz">1</span>
                        </div>
                        <span class="text-sm block text-center">Grass pollen</span>
                    </div>
                    <div class="pl-app__heroine__pollen-levels__items__item">
                        <div class="pl-app__heroine__pollen-levels__items__item__box bg-signal-low text-white">
                            <span class="fugaz">2</span>
                        </div>
                        <span class="text-sm block text-center">Tree pollen</span>
                    </div>
                    <div class="pl-app__heroine__pollen-levels__items__item">
                        <div class="pl-app__heroine__pollen-levels__items__item__box bg-signal-moderate text-black">
                            <span class="fugaz">3</span>
                        </div>
                        <span class="text-sm block text-center">Weed pollen</span>
                    </div>
                </div>
                <p class="mt-2 leading-tight">Pollen levels low right now. It's a great day to enjoy the outdoors!</p>
            </div>
        </div>
    </div>
</template>
