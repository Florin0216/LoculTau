import {onMounted, ref} from "vue";
import RoomService from "../services/RoomService";

export function useRooms() {
    const rooms = ref([]);

    const getRooms = () => {
        RoomService
            .listAdmin()
            .then((response) => {
                rooms.value = response.data.data;
            })
    }

    onMounted(() => {
        getRooms();
    })

    return {
        rooms,
        getRooms,
    }
}
