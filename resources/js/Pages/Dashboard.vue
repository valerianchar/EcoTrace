<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Map from "@/Components/Map.vue";
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <Map />
  </AuthenticatedLayout>
</template>
