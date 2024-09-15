<template>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper" style="min-height: 100vh;">
      <div class="sidebar-heading text-light p-3">Hotel Managemenet</div>
      <div class="list-group list-group-flush">
        <nuxt-link to="/" class="list-group-item list-group-item-action" active-class="active">Dashboard</nuxt-link>
        <nuxt-link to="/room" class="list-group-item list-group-item-action" active-class="active">Rooms</nuxt-link>
        <nuxt-link to="/guest" class="list-group-item list-group-item-action" active-class="active">Guests</nuxt-link>
        <nuxt-link to="/reservation" class="list-group-item list-group-item-action" active-class="active">Reservations</nuxt-link>
      </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
      <app-header />
      <slot />
    </div>
  </div>

</template>

<script setup>
import AppHeader from "~/layouts/components/AppHeader.vue";

const route = useRoute();
</script>

<style scoped>
/* Custom styles */
#wrapper.toggled #sidebar-wrapper {
  margin-left: -250px;
}
#sidebar-wrapper {
  min-width: 250px;
  max-width: 250px;
}
#page-content-wrapper {
  flex: 1;
}
</style>