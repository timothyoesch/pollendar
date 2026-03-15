<script setup>
import { Icon } from "@iconify/vue";
import { defineProps } from "vue";

const props = defineProps({
    entry: Object
});

let date = new Date(props.entry.date);
let parts = {
    day : date.getDate(),
    month : date.toLocaleDateString("en", { month: 'long' }),
    dayOfWeek : date.toLocaleDateString("en", { weekday: 'long' })
};

let averageUpi = 0;
if (props.entry.pollen_forecast) {
    let upis = {};
    if (props.entry.pollen_forecast.tree_upi != null) {
        upis["Tree pollen"] = props.entry.pollen_forecast.tree_upi;
    }
    if (props.entry.pollen_forecast.grass_upi != null) {
        upis["Grass pollen"] = props.entry.pollen_forecast.grass_upi;
    }
    if (props.entry.pollen_forecast.weed_upi != null) {
        upis["Weed pollen"] = props.entry.pollen_forecast.weed_upi;
    }
    averageUpi = Math.round(Object.values(upis).reduce((a, b) => a + b, 0) / Object.values(upis).length);
}

</script>
<template>
    <div class="pl-app__entry flex gap-x-4 items-center p-2 border-2 border-primary-200 rounded-lg">
        <div class="pl-app__entry__date w-34 aspect-square bg-secondary-400 flex-col flex items-stretch justify-center text-center text-white">
            <span class="pl-app__entry__date__day-of-week">{{ parts.dayOfWeek }}</span>
            <span class="pl-app__entry__date__day fugaz text-7xl leading-[0.85] -mb-1 mt-1 font-bold">{{ parts.day }}</span>
            <span class="pl-app__entry__date__month">{{ parts.month }}</span>
        </div>
        <div class="pl-app__entry__content">
            <div class="flex gap-x-1">
                <span
                    class="h-6 w-6 flex justify-center items-center rounded-sm"
                    :class="'pl-app__severity-pill-'+props.entry.symptoms_severity"
                >{{ props.entry.symptoms_severity }}</span>
                <p v-if="props.entry.symptoms_severity === 0">No symptoms</p>
                <p v-if="props.entry.symptoms_severity === 1">Very mild symptoms</p>
                <p v-else-if="props.entry.symptoms_severity === 2">Mild symptoms</p>
                <p v-else-if="props.entry.symptoms_severity === 3">Moderate symptoms</p>
                <p v-else-if="props.entry.symptoms_severity === 4">Severe symptoms</p>
                <p v-else-if="props.entry.symptoms_severity === 5">Very severe symptoms</p>
            </div>
            <p v-if="props.entry.symptoms" class="mt-1 text-sm text-gray-400">{{ props.entry.symptoms.join(", ") }}</p>
            <div class="flex gap-x-1 mt-2">
                <Icon icon="heroicons:check-circle-20-solid" class="text-primary-400 w-6 h-6" v-if="props.entry.medication_taken" />
                <Icon icon="heroicons:x-circle-20-solid" class="text-secondary-400 w-6 h-6" v-else />
                <p>{{ props.entry.medication_taken ? "Took medication" : "Did not take medication" }}</p>
            </div>
            <div class="flex gap-x-1 mt-2" v-if="props.entry.pollen_forecast">
                <span
                    class="h-6 w-6 flex justify-center items-center rounded-sm"
                    :class="'pl-app__severity-pill-'+averageUpi"
                >{{ averageUpi }}</span>
                <p v-if="averageUpi === 1">Low pollen level</p>
                <p v-else-if="averageUpi === 2">Moderate pollen level</p>
                <p v-else-if="averageUpi === 3">High pollen level</p>
                <p v-else-if="averageUpi === 4">Very high pollen level</p>
                <p v-else-if="averageUpi === 5">Extreme pollen level</p>
            </div>
        </div>
    </div>
</template>

<style>
.pl-app__entry__date {
    flex-shrink: 0;
}
</style>
