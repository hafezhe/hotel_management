<template>
  <div class="container-fluid my-3">
    <div class="row">
      <div class="col-sm-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <form @submit.prevent.stop="submitForm">
              <div class="row">
                <div class="col-sm-12">
                  <h5>Create Room</h5>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" id="code" class="form-control" maxlength="6" v-model="form.code">
                  </div>
                </div>
                <div class="col-sm-6 my-2">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" v-model="form.status" id="status">
                      <option value="ready">Ready</option>
                      <option value="pending_cleanup">Pending Cleanup</option>
                      <option value="reserved">Reserved</option>
                    </select>
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

const config = useRuntimeConfig()
const router = useRouter()

const form = ref({
  code: '',
  status: '',
});

const { $api } = useNuxtApp()

const submitForm = () => {
  $api.create('/room/store',form.value).then((value) => {
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

      router.push('/room');
    }
  })
}

</script>

<style scoped>

</style>
