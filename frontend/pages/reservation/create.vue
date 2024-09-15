<template>
  <div class="container-fluid my-3">
    <div class="row">
      <div class="col-sm-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <form @submit.prevent.stop="submitForm">
              <div class="row">
                <div class="col-sm-12">
                  <h5>Create Reservation</h5>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" class="form-control" v-model="form.fname" placeholder="First Name ...">
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" class="form-control" v-model="form.lname" placeholder="Last Name ...">
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" class="form-control" v-model="form.phone" placeholder="Phone ...">
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="room_id">Room</label>
                    <select class="form-control" v-model="form.room_id" id="room_id">
                      <option v-for="(room,index) in rooms" :key="index" :value="room.id">{{ room.code }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="datetime-local" id="start_date" class="form-control" v-model="form.start_date" placeholder="Start Date ...">
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="datetime-local" id="end_date" class="form-control" v-model="form.end_date" placeholder="End Date ...">
                  </div>
                </div>

                <div class="col-sm-12 my-2 d-flex align-items-center justify-content-end">
                  <button type="submit" class="btn btn-dark">Create</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Swal from "sweetalert2/dist/sweetalert2.js";
import {onMounted} from "vue";

const config = useRuntimeConfig()
const router = useRouter()

const form = ref({
  room_id: '',
  fname: '',
  lname: '',
  phone: '',
});

const { $api } = useNuxtApp()

const submitForm = () => {
  $api.create('/reservation/store',form.value).then((value) => {
    if(value.data.status) {
      Swal.fire({
        toast: true,
        position: "top-right",
        icon: "success",
        title: "Success",
        text: "Item Successfully Created!",
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true,
      });

      router.push('/reservation');
    }
  })
}


const rooms = ref([]);

const getData = () => {
  $api.create("/reservation/room",{index: 1}).then((value) => {
    rooms.value = value.data.data;
  });
}

onMounted(() => {
  getData();
})

</script>

<style scoped>

</style>
