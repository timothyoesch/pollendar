<script setup>
import BottomBar from "@/Components/BottomBar.vue"
import AppHeroine from "../../Components/AppHeroine.vue";
import TopBar from "../../Components/TopBar.vue";
import Entry from "../../Components/Entry.vue";
import { onMounted } from "vue";

const props = defineProps({
    entries: Array,
    todays_forecast: Object
});

</script>
<template>
    <TopBar />
    <AppHeroine :pollen-forecast="props.todays_forecast" />
    <div class="pl-app__container grid grid-cols-1 gap-4 mt-12 pb-40">
        <Entry v-for="entry in props.entries" :key="entry.id" :entry="entry" />
    </div>
    <BottomBar />
</template>
