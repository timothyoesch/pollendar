<script setup>
import { Icon } from '@iconify/vue';
const props = defineProps({
    pollenForecast: Object
});
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
                <div class="pl-app__heroine__pollen-levels__items flex justify-around">
                    <div
                        v-if="props.pollenForecast?.grass_upi !== null"
                        class="pl-app__heroine__pollen-levels__items__item"
                        :class="'pl-app_heroine__pollen-levels__items__item-severity-' + props.pollenForecast?.grass_upi"
                    >
                        <div class="pl-app__heroine__pollen-levels__items__item__box">
                            <span class="fugaz">{{ props.pollenForecast?.grass_upi }}</span>
                        </div>
                        <span class="text-sm block text-center">Grass pollen</span>
                    </div>
                    <div
                        v-if="props.pollenForecast?.tree_upi !== null"
                        class="pl-app__heroine__pollen-levels__items__item"
                        :class="'pl-app_heroine__pollen-levels__items__item-severity-' + props.pollenForecast?.tree_upi"
                    >
                        <div class="pl-app__heroine__pollen-levels__items__item__box">
                            <span class="fugaz">{{ props.pollenForecast?.tree_upi }}</span>
                        </div>
                        <span class="text-sm block text-center">Tree pollen</span>
                    </div>
                    <div
                        v-if="props.pollenForecast?.weed_upi !== null"
                        class="pl-app__heroine__pollen-levels__items__item"
                        :class="'pl-app_heroine__pollen-levels__items__item-severity-' + props.pollenForecast?.weed_upi"
                    >
                        <div class="pl-app__heroine__pollen-levels__items__item__box">
                            <span class="fugaz">{{ props.pollenForecast?.weed_upi }}</span>
                        </div>
                        <span class="text-sm block text-center">Weed pollen</span>
                    </div>
                </div>
                <p class="mt-2 leading-tight">{{ props.pollenForecast?.health_recommendations }}</p>
            </div>
        </div>
    </div>
</template>
