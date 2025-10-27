<script setup>

import {onMounted, ref} from "vue";
import Routing from "fos-router";
import SecurityService from "../services/SecurityService";
import {isAdmin} from "../helpers/isAdmin";

const isOpen = ref(false);

const user = ref(null);

onMounted(() => {
    SecurityService
        .getUser()
        .then((response) => {
            user.value = response.data.data;
        })
})

</script>

<template>
    <nav class="bg-gray-950 text-white sticky top-0 z-50 shadow-lg w-full">
        <div class="flex justify-between items-center px-4 lg:px-18 py-4">
            <a :href="Routing.generate('public_app_homepage')" class="flex items-center gap-1 lg:w-64 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2a7 7 0 0 0-7 7v3.586l-1.707 1.707A1 1 0 0 0 4 16h16a1 1 0 0 0 .707-1.707L19 12.586V9a7 7 0 0 0-7-7Zm0 20a3 3 0 0 0 2.995-2.824L15 19h-6a3 3 0 0 0 2.824 2.995L12 22Z"/>
                </svg>
                <div class="font-bold text-2xl tracking-wide">DeBine</div>
            </a>
            <div
                class="lg:flex justify-between flex-col lg:flex-row md:items-center font-medium text-lg absolute lg:static top-0 left-0 w-screen h-screen lg:h-auto bg-gray-950 lg:bg-transparent pt-24 lg:pt-0 z-40 lg:transition-all lg:duration-300"
                :class="isOpen ? 'flex' : 'hidden'"
            >
                <ul class="flex flex-col lg:flex-row gap-6 lg:gap-6 items-center">
                    <li><a :href="Routing.generate('public_app_content_about')" class="hover:text-yellow-300 lg:duration-300">Despre</a></li>
                    <li><a :href="Routing.generate('seating_event_list')" class="hover:text-yellow-300 lg:duration-300">Evenimente</a></li>
                    <li><a :href="Routing.generate('public_app_gallery_list')" class="hover:text-yellow-300 lg:duration-300">Galerie</a></li>
                    <li><a :href="Routing.generate('public_app_feedback_show')" class="hover:text-yellow-300 lg:duration-300">Contact</a></li>
                </ul>
                <ul class="flex flex-col md:flex-row justify-end gap-4 items-center p-4 lg:p-1 lg:mt-0 w-full lg:w-auto">
                    <li v-if="!user" class="w-full text-center">
                        <a :href="Routing.generate('user_security_login')" class="block hover:border-white border py-2 px-4 rounded-full border-gray-400 lg:duration-300">
                            Autentificare
                        </a>
                    </li>
                    <li v-if="isAdmin(user)" class="w-full text-center">
                        <a :href="Routing.generate('admin_app_homepage')" class="block hover:border-white border py-2 px-4 rounded-full border-gray-400 lg:duration-300">
                            Administrare
                        </a>
                    </li>
<!--                    <li class="w-full md:w-1/2 text-center">-->
<!--                        <a href="#" class="block hover:bg-green-600 py-2 px-4 rounded-full bg-green-700 lg:duration-300">-->
<!--                            Inregistare-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </div>
            <button @click="isOpen = !isOpen" class="lg:hidden flex flex-col justify-center gap-1.5 items-center z-50">
               <span
                   class="h-0.5 w-6 rounded-full bg-white transition"
                   :class="isOpen ? 'rotate-45 translate-y-[8px]' : ''"
               ></span>
                <span
                    class="h-0.5 w-6 rounded-full bg-white transition"
                    :class="isOpen ? 'opacity-0' : ''"
                ></span>
                <span
                    class="h-0.5 w-6 rounded-full bg-white transition"
                    :class="isOpen ? '-rotate-45 -translate-y-[8px]' : ''"
                ></span>
            </button>
        </div>
    </nav>
</template>
