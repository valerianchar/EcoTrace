<template>
  <div class="max-w-xs text-black">
    <h2 class="text-lg font-bold mb-2">{{ title }}</h2>
    <p class="text-sm mb-4">{{ description }}</p>

    <div class="grid grid-cols-2 gap-2">
      <img
        v-for="(image, index) in images"
        :key="index"
        :src="image"
        alt="Image du lieu"
        class="w-full h-24 object-cover rounded"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: "Titre du lieu",
  },
  description: {
    type: String,
    default: "Description du lieu sélectionné.",
  },
});

const images = [
  "https://source.unsplash.com/random/300x200?city",
  "https://source.unsplash.com/random/300x200?nature",
  "https://source.unsplash.com/random/300x200?architecture",
  "https://source.unsplash.com/random/300x200?landscape",
];
</script>

<style scoped></style>
