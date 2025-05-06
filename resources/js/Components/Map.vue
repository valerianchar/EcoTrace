<template>
  <div class="relative w-full h-screen bg-gray-800 rounded-xl overflow-hidden">
    <LMap
      ref="map"
      :options="{ zoomControl: false }"
      :zoom="zoom"
      :center="centerUser"
      class="h-full w-full z-0"
      @click="updateMarkerPosition"
    >
      <LTileLayer :url="tileUrl" :attribution="attribution" />

      <LMarker v-if="clickedPosition" :lat-lng="clickedPosition">
        <LPopup>
          <PointCard
            :title="infoClickedPosition[0]"
            :description="infoClickedPosition[1]"
            :info="infoClickedPosition[2]"
          />
        </LPopup>
      </LMarker>
    </LMap>
  </div>
</template>

<script setup>
import { LMap, LTileLayer, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";
import { ref, watch } from "vue";
import PointCard from "@/Components/PointCard.vue";
import useUserLocation from "@/Utils/useUserLocation.js";
import axios from "axios";
import { info } from "autoprefixer";

const props = defineProps({
  center: {
    type: Array,
    default: () => [45.764043, 4.835659],
  },
  zoom: {
    type: Number,
    default: 13,
  },
});
// Ref map
const map = ref();

const tileUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
const attribution = "&copy; OpenStreetMap contributors";

//Cliked Position
const clickedPosition = ref(null);
const infoClickedPosition = ref(null);

const updateMarkerPosition = (event) => {
  const { latlng } = event;
  clickedPosition.value = [latlng.lat, latlng.lng];
  refreshPrompt(latlng);
};

const refreshPrompt = async (latlng) => {
  infoClickedPosition.value = null;
  await axios
    .post(
      route("promptGenerator", {
        lat: latlng.lat,
        lon: latlng.lng,
      })
    )
    .then((reponse) => {
      infoClickedPosition.value = reponse.data;
    });
};

// Center User
const centerUser = ref(props.center);
const { userPosition, permissionStatus } = useUserLocation();

watch(permissionStatus, (status) => {
  if (status === "granted" && userPosition.value) {
    centerUser.value = [userPosition.value.latitude, userPosition.value.longitude];
  }
});
</script>
