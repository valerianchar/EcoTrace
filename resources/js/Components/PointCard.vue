<template>
  <div class="max-w-xs text-black">
    <h2 class="text-lg font-bold mb-2">{{ title }}</h2>
    <h4 class="font-bold">Description</h4>
    <div class="text-sm mb-4">{{ description }}</div>
    <h4 class="font-bold">Informations</h4>
    <div class="text-sm mb-4">{{ info }}</div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
  },
  description: {
    type: String,
  },
  info: {
    type: String,
  },
});
</script>

<style scoped></style>
