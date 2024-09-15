<template>
  <div class="container-fluid my-3">
    <div class="row">
      <div class="col-sm-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12 my-2 d-flex align-items-center justify-content-between">
                <h5>Reservation List</h5>
                <nuxt-link to="/reservation/create" class="btn btn-primary">Create Reservation</nuxt-link>
              </div>
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="code">Code</label>
                      <input type="text" class="form-control" placeholder="Search Code ..." @keyup="getData" v-model="meta.search.code">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" placeholder="Search Code ..." @keyup="getData" v-model="meta.search.name">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 my-2">
                <table class="table table-striped table-hover table-bordered">
                  <thead>
                  <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Room</th>
                    <th scope="col" class="text-center">Name</th>
                    <th scope="col" class="text-center">Start Date</th>
                    <th scope="col" class="text-center">End Date</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <template v-if="data.data.length > 0">
                    <tr v-for="(reservation,index) in data.data" :key="index">
                      <th scope="row" class="text-center align-middle">{{ meta.page * (index + 1) }}</th>
                      <td class="text-center align-middle">{{ reservation.room.code }}</td>
                      <td class="text-center align-middle">{{ reservation.guest.fname }} {{ reservation.guest.lname }}</td>
                      <td class="text-center align-middle">{{ reservation.start_date }}</td>
                      <td class="text-center align-middle">{{ reservation.end_date }}</td>
                      <td class="text-center align-middle">
                        <nuxt-link  class="btn btn-info mx-2" :to="'/reservation/edit/' + reservation.id">
                          <i class="fa fa-edit"></i>
                        </nuxt-link>
                        <button class="btn btn-danger mx-2" @click="removeItem(reservation.id)">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </template>
                  <template v-else>
                    <tr>
                      <td colspan="6" class="text-center">There is No Reservations....</td>
                    </tr>
                  </template>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-12 d-flex align-items-center justify-content-center" >
                <nav aria-label="Page navigation example">
                  <ul class="pagination">
                    <li class="page-item">
                      <a
                          class="page-link"
                          :class="{ disabled: meta.page === 1 }"
                          href="#"
                          :disabled="meta.page === 1"
                          @click="prevPage"
                          aria-label="Previous"
                      >
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li
                        class="page-item"
                        v-for="(page, index) in meta.links"
                        :key="index"
                        v-show="index !== 0 && index !== meta.links.length - 1"
                    >
                      <a
                          class="page-link"
                          href="#"
                          @click="selectPage(page.label)"
                          :class="{ active: page.active }"
                      >{{ page.label }}</a
                      >
                    </li>
                    <li class="page-item">
                      <a
                          class="page-link"
                          href="#"
                          :class="{
                        disabled: meta.page === meta.links.length - 2,
                      }"
                          @click="nextPage"
                          :disabled="meta.page === meta.links.length - 2"
                          aria-label="Next"
                      >
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Swal from "sweetalert2/dist/sweetalert2.js";

const config = useRuntimeConfig()
const route = useRoute();
const router = useRouter();

const meta = reactive({
  search: {
    code: ""
  },
  page: 1,
  links: []
});

const { data } = await useAsyncData(`reservations`, () =>
    $fetch(`${config.public.apiBaseUrl}/reservation`, {
      method: 'POST',
      body: {
        page: meta.page
      },
    })
);

router.push({
  path: route.fullPath,
  query: { page: meta.page, ...meta.search },
});

const { $api } = useNuxtApp();

const getData = () => {
  $api.create("/reservation?page=" + meta.page, meta.search).then((value) => {
    data.value = value.data;
    console.log(data.value)

    router.push({
      path: route.fullPath,
      query: { page: meta.page, ...meta.search },
    });
    meta.links = value.data.meta.links;
  });
}

const prevPage = () => {
  if (meta.page !== 1) {
    meta.page--;
  }
  getData();

  router.push({
    path: route.fullPath,
    query: { page: meta.page, ...meta.search },
  });
};

const selectPage = (page) => {
  if (page !== meta.page) {
    meta.page = page;
    getData();

    router.push({
      path: route.fullPath,
      query: { page: meta.page, ...meta.search },
    });
  }
};

const nextPage = () => {
  if (meta.page !== meta.links.length) {
    meta.page++;
  }
  getData();

  router.push({
    path: route.fullPath,
    query: { page: meta.page, ...meta.search },
  });
};

const removeItem = (id) => {
  Swal.fire({
    title: "Are You Sure ?",
    text: "You Can not Bring it back!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Do it",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      data.loaded = false;
      $api.create("/reservation/delete", {
        id: id
      }).then((value) => {
        getData();

        Swal.fire({
          toast: true,
          position: "top-right",
          icon: "success",
          title: "موفقیت",
          text: "Item Successfully Deleted!",
          showConfirmButton: false,
          timer: 1000,
          timerProgressBar: true,
        });
      });
    }
  });
};

if(typeof data.value.meta !== 'undefined') {
  meta.links = data.value.meta.links;
}

</script>

<style scoped>

</style>
