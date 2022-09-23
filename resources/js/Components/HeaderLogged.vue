<script setup>
import {Link} from "@inertiajs/inertia-vue3"
import {ref} from "vue";

const toggleOn = ref(false)
const toggleMobileMenuOn = ref(false)
const DropdownMenuOn = ref(false)

const handleToggleLink = () => {
    toggleOn.value = !toggleOn.value
}

const handleToggleMobileMenu = () => {
    toggleMobileMenuOn.value = !toggleMobileMenuOn.value
}

const handleToggleDropdownMenu = () => {
    DropdownMenuOn.value = !DropdownMenuOn.value
}

</script>

<template>
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-20 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button @click="handleToggleMobileMenu"
                            type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700
                                   hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!--
                          Icon when menu is closed.

                          Heroicon name: outline/bars-3

                          Menu open: "hidden", Menu closed: "block"
                        -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <!--
                          Icon when menu is open.

                          Heroicon name: outline/x-mark

                          Menu open: "block", Menu closed: "hidden"
                        -->
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <img class="block h-8 w-auto lg:hidden"
                             src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                    </div>
                    <div class="hidden sm:ml-10 sm:block">
                        <div class="flex space-x-4">
                            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                            <a href="#" class="bg-gray-900 text-white px-3 py-2 rounded-md font-medium"
                               aria-current="page">Home</a>

                            <a href="#"
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md font-medium">Team</a>

                            <a href="#"
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md font-medium">Projects</a>

                            <div class="relative">
                                <!-- Item active: "text-gray-900", Item inactive: "text-gray-500" -->
                                <button @click="handleToggleLink" type="button"
                                        class="group inline-flex items-center text-base text-gray-300 hover:bg-gray-700
                                               hover:text-white px-3 py-2 rounded-md font-medium"
                                        aria-expanded="false">
                                    <span>Kalkulatory</span>
                                    <!--
                                      Heroicon name: mini/chevron-down

                                      Item active: "text-gray-600", Item inactive: "text-gray-400"
                                    -->
                                    <svg class="text-gray-300 ml-2 h-5 w-5 group-hover:text-white"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <!--
                                  'More' flyout menu, show/hide based on flyout menu state.

                                  Entering: "transition ease-out duration-200"
                                    From: "opacity-0 translate-y-1"
                                    To: "opacity-100 translate-y-0"
                                  Leaving: "transition ease-in duration-150"
                                    From: "opacity-100 translate-y-0"
                                    To: "opacity-0 translate-y-1"
                                -->
                                <div v-show="toggleOn"
                                     class="absolute left-1/2 z-10 mt-3 w-screen max-w-md -translate-x-1/2 transform px-2 sm:px-0">
                                    <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">
                                            <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                                <!-- Heroicon name: outline/lifebuoy -->
                                                <svg class="h-6 w-6 flex-shrink-0 text-indigo-600"
                                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 00-1.652 1.306A9.025 9.025 0 004.33 7.288"/>
                                                </svg>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Help Center</p>
                                                    <p class="mt-1 text-sm text-gray-500">Get all of your questions
                                                        answered in
                                                        our forums or contact support.</p>
                                                </div>
                                            </a>

                                            <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                                <!-- Heroicon name: outline/bookmark-square -->
                                                <svg class="h-6 w-6 flex-shrink-0 text-indigo-600"
                                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9"/>
                                                </svg>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Guides</p>
                                                    <p class="mt-1 text-sm text-gray-500">Learn how to maximize our
                                                        platform to
                                                        get the most out of it.</p>
                                                </div>
                                            </a>

                                            <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                                <!-- Heroicon name: outline/calendar -->
                                                <svg class="h-6 w-6 flex-shrink-0 text-indigo-600"
                                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                                </svg>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Events</p>
                                                    <p class="mt-1 text-sm text-gray-500">See what meet-ups and other
                                                        events we
                                                        might be planning near you.</p>
                                                </div>
                                            </a>

                                            <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                                <!-- Heroicon name: outline/shield-check -->
                                                <svg class="h-6 w-6 flex-shrink-0 text-indigo-600"
                                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                                </svg>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Security</p>
                                                    <p class="mt-1 text-sm text-gray-500">Understand how we take your privacy seriously.</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden items-center justify-end md:flex md:flex-1 lg:w-0">
                        <h1 class="text-sm text-xl font-semibold text-white">{{ $page.props.auth.user.name.toUpperCase() }}</h1>
                        <div class="relative ml-3">
                            <div>
                                <button
                                    @click="handleToggleDropdownMenu"
                                    type="button" class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                </button>
                            </div>

                            <!--
                              Dropdown menu, show/hide based on menu state.

                              Entering: "transition ease-out duration-100"
                                From: "transform opacity-0 scale-95"
                                To: "transform opacity-100 scale-100"
                              Leaving: "transition ease-in duration-75"
                                From: "transform opacity-100 scale-100"
                                To: "transform opacity-0 scale-95"
                            -->
                            <div v-show="DropdownMenuOn"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                                <Link :href="route('logout')" method="post" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Wyloguj się</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div v-show="toggleMobileMenuOn"
             class="sm:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium"
                   aria-current="page">Dashboard</a>

                <a href="#"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>

                <a href="#"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>

                <a href="#"
                   class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>
            </div>
            <div class="space-y-6 py-6 px-5">
                <div>
                    <Link :href="route('register')"
                          class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                        Zarejestruj się
                    </Link>
                    <p class="mt-6 text-center text-base font-medium text-gray-500">
                        Masz już konto?
                        <Link :href="route('login')" class="text-indigo-600 hover:text-indigo-500">Zaloguj się</Link>
                    </p>
                </div>
            </div>
        </div>
    </nav>
</template>
