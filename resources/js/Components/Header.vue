<script setup>
import {Link, usePage} from "@inertiajs/inertia-vue3";
import {computed, ref} from "vue";
import {onClickOutside} from "@vueuse/core";
import NavLink from "@/Components/NavLink.vue";

const auth = computed(() => usePage().props.value.auth);
const loggedIn = auth.value.loggedIn;
const isAdmin = auth.value.isAdmin;

// nav dropdown
const toggleOnNavLinks = ref(false);
const targetNavLinks = ref(null);

onClickOutside(targetNavLinks, () => {
  toggleOnNavLinks.value = false;
});

const handleToggleOnNavLinks = () => {
  toggleOnNavLinks.value = !toggleOnNavLinks.value;
};

// mobile menu
const toggleMobileMenuOn = ref(false);

const handleToggleMobileMenu = () => {
  toggleMobileMenuOn.value = !toggleMobileMenuOn.value;
};

// profile dropdown
const toggleOnProfile = ref(false);
const targetProfile = ref(null);

onClickOutside(targetProfile, () => {
  toggleOnProfile.value = false;
});

const handleToggleProfile = () => {
  toggleOnProfile.value = !toggleOnProfile.value;
};
</script>

<template>
  <nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
      <div class="relative flex h-20 items-center justify-between">
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <!-- Mobile menu button-->
          <button
            @click="handleToggleMobileMenu"
            type="button"
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            aria-controls="mobile-menu"
            aria-expanded="false"
          >
            <span class="sr-only">Open main menu</span>
            <!--
                          Icon when menu is closed.

                          Heroicon name: outline/bars-3

                          Menu open: "hidden", Menu closed: "block"
                        -->
            <svg
              v-show="!toggleMobileMenuOn"
              class="block h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
              />
            </svg>
            <!--
                          Icon when menu is open.

                          Heroicon name: outline/x-mark

                          Menu open: "block", Menu closed: "hidden"
                        -->
            <svg
              v-show="toggleMobileMenuOn"
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div
          class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start"
        >
          <div class="flex flex-shrink-0 items-center">
            <img src="@/../images/loan-logo.png" alt="" width="45" height="45"/>
          </div>
          <div class="hidden sm:ml-10 sm:block">
            <div class="flex space-x-4">
              <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
              <NavLink :href="route('home')" :active="$page.component === 'Home'">
                Home
              </NavLink>

              <NavLink :href="route('offer')" :active="$page.component === 'Offer'">
                Oferta
              </NavLink>

              <NavLink
                :href="route('about-credit')"
                :active="$page.component === 'AboutCredit'"
              >
                O Kredycie
              </NavLink>

              <div class="relative" ref="targetNavLinks">
                <!-- Item active: "text-gray-900", Item inactive: "text-gray-500" -->
                <button
                  @click="handleToggleOnNavLinks"
                  type="button"
                  class="group inline-flex items-center text-base text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md font-medium"
                  aria-expanded="false"
                >
                  <span>Kalkulatory</span>
                  <!--
                                      Heroicon name: mini/chevron-down

                                      Item active: "text-gray-600", Item inactive: "text-gray-400"
                                    -->
                  <svg
                    class="text-gray-300 ml-2 h-5 w-5 group-hover:text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>

                <transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="opacity-0 translate-y-1"
                  enter-to-class="opacity-100 translate-y-0"
                  leave-active-class="transition ease-in duration-150"
                  leave-from-class="opacity-100 translate-y-0"
                  leave-to-class="opacity-0 translate-y-1"
                >
                  <div
                    v-show="toggleOnNavLinks"
                    class="absolute left-1/2 z-10 mt-3 w-screen max-w-md -translate-x-1/2 transform px-2 sm:px-0"
                  >
                    <div
                      class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                    >
                      <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">
                        <Link
                          :href="route('calculator.installment')"
                          class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-100"
                        >
                          <!-- Heroicon name: outline/lifebuoy -->

                          <svg
                            class="h-6 w-6 flex-shrink-0 text-indigo-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            strokeWidth="{1.5}"
                            stroke="currentColor"
                            className="w-6 h-6"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z"
                            />
                          </svg>

                          <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                              Kalkulator raty
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                              Oblicz miesięczną rate kredytu hipotecznego.
                            </p>
                          </div>
                        </Link>

                        <Link
                          :href="route('calculator.rrso')"
                          class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-100"
                        >
                          <svg
                            class="h-6 w-6 flex-shrink-0 text-indigo-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            strokeWidth="{1.5}"
                            stroke="currentColor"
                            className="w-6 h-6"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z"
                            />
                          </svg>

                          <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                              Kalkulator RRSO
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                              Oblicz rzeczywistą roczną stopę procentową.
                            </p>
                          </div>
                        </Link>

                        <Link
                          :href="route('calculator.extended')"
                          class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-100"
                        >
                          <svg
                            class="h-6 w-6 flex-shrink-0 text-indigo-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            strokeWidth="{1.5}"
                            stroke="currentColor"
                            className="w-6 h-6"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z"
                            />
                          </svg>

                          <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">
                              Kalkulator Rozszerzony
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                              Oblicz miesięczną rate kredytu hipotecznego oraz harmonogram
                              spłaty.
                            </p>
                          </div>
                        </Link>

                        <Link
                          :href="route('calculator.overpayment')"
                          class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50"
                        >
                          <svg
                            class="h-6 w-6 flex-shrink-0 text-indigo-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                            />
                          </svg>
                          <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">Nadpłata kredytu</p>
                            <p class="mt-1 text-sm text-gray-500">
                              Understand how we take your privacy seriously.
                            </p>
                          </div>
                        </Link>
                      </div>
                    </div>
                  </div>
                </transition>
              </div>
            </div>
          </div>
          <div
            v-if="!loggedIn"
            class="hidden items-center justify-end md:flex md:flex-1 lg:w-0"
          >
            <Link
              :href="route('login')"
              class="whitespace-nowrap text-base font-medium text-white px-4 py-2 hover:bg-gray-700 rounded-lg"
            >
              Zaloguj się
            </Link>
            <Link
              :href="route('register')"
              class="ml-2 inline-flex items-center justify-center whitespace-nowrap rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
            >
              Zarejestruj się
            </Link>
          </div>
          <div
            v-if="loggedIn"
            class="hidden items-center justify-end sm:flex sm:flex-1 lg:w-0"
          >
            <div class="ml-4 flex items-center md:ml-6">
              <!-- Profile dropdown -->
              <div class="relative ml-3" ref="targetProfile">
                <div>
                  <button
                    @click="handleToggleProfile"
                    type="button"
                    class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                    id="user-menu-button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <span class="sr-only">Open user menu</span>
                    <img
                      class="h-10 w-10 rounded-full"
                      src="https://img.icons8.com/fluency/96/null/user-male-circle.png"
                      alt="profile"
                    />
                  </button>
                </div>
                <transition
                  enter="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-to-class="transform opacity-0 scale-95"
                  leave-from-class="transform opacity-100 scale-100"
                >
                  <div
                    v-show="toggleOnProfile"
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu-button"
                    tabindex="-1"
                  >
                    <!-- Active: "bg-gray-100", Not Active: "" -->

                    <Link
                      :href="route('profil')"
                      class="block px-4 py-2 text-sm text-gray-700"
                    >Twój Profil
                    </Link>

                    <Link
                      v-if="loggedIn"
                      :href="route('profil.saved-simulations')"
                      class="block px-4 py-2 text-sm text-gray-700"
                    >Twoje kalkulacje
                    </Link>

                    <Link
                      v-if="isAdmin"
                      :href="route('admin.dashboard')"
                      class="block px-4 py-2 text-sm text-gray-700"
                    >Dashboard
                    </Link>

                    <Link
                      :href="route('logout')"
                      method="POST"
                      class="block px-4 py-2 text-sm text-gray-700"
                      as="button"
                    >Wyloguj się
                    </Link>
                  </div>
                </transition>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div v-show="toggleMobileMenuOn" class="sm:hidden">
      <div class="space-y-1 px-2 pt-2 pb-3">
        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

        <NavLink class="block" :href="route('home')" :active="$page.component === 'Home'">
          Home
        </NavLink>

        <NavLink
          class="block"
          :href="route('offer')"
          :active="$page.component === 'Offer'"
        >
          Oferta
        </NavLink>

        <NavLink
          class="block"
          :href="route('about-credit')"
          :active="$page.component === 'AboutCredit'"
        >
          O Kredycie
        </NavLink>
      </div>
      <div v-if="!loggedIn" class="space-y-6 py-6 px-5 border-t border-gray-700">
        <div>
          <Link
            :href="route('register')"
            class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
          >
            Zarejestruj się
          </Link>
          <p class="mt-6 text-center text-base font-medium text-gray-500">
            Masz już konto?
            <Link :href="route('login')" class="text-indigo-600 hover:text-indigo-500"
            >Zaloguj się
            </Link
            >
          </p>
        </div>
      </div>
      <div v-if="loggedIn" class="border-t border-gray-700 pt-4 pb-3">
        <div class="flex items-center px-5">
          <div class="flex-shrink-0">
            <img
              class="h-10 w-10 rounded-full"
              src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
              alt=""
            />
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">Tom Cook</div>
            <div class="text-sm font-medium leading-none text-gray-400">
              tom@example.com
            </div>
          </div>
          <button
            type="button"
            class="ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            <span class="sr-only">View notifications</span>
            <!-- Heroicon name: outline/bell -->
            <svg
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
              />
            </svg>
          </button>
        </div>
        <div class="mt-3 space-y-1 px-2">
          <a
            href="#"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
          >
            Your Profile</a
          >

          <a
            href="#"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
          >
            Settings</a
          >

          <Link
            :href="route('logout')"
            method="POST"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
            as="button"
          >
            Wyloguj się
          </Link>
        </div>
      </div>
    </div>
  </nav>
</template>
