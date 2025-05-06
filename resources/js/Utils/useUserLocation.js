import { ref } from 'vue'

export default function useUserLocation() {
    const userPosition = ref(null)
    const error = ref(null)
    const permissionStatus = ref(null)

    const updatePermission = () => {
        if (!navigator.permissions) {
            permissionStatus.value = 'prompt'
            return
        }
        navigator.permissions.query({ name: 'geolocation' }).then((result) => {
            if (result.state === 'granted' || result.state === 'prompt') {
                requestLocation()
            }
        })
    }

    updatePermission()

    const requestLocation = () => {
        if (!navigator.geolocation) {
            error.value = "La géolocalisation n'est pas supportée par ce navigateur."
            permissionStatus.value = 'unsupported'
            return
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                userPosition.value = position.coords
                permissionStatus.value = 'granted'
                error.value = null
            },
            (err) => {
                error.value = "Erreur de géolocalisation : " + err.message
                permissionStatus.value = 'denied'
            }
        )
    }

    return {
        userPosition,
        error,
        permissionStatus,
    }
}
