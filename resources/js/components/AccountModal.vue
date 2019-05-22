<template>
  <div>
    <!-- Account Modal -->
    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Account</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"
                  ><i class="fa fa-envelope"></i
                ></span>
              </div>
              <input
                autocomplete="off"
                type="email"
                class="form-control"
                placeholder="Email"
                name="email"
                v-model="form.email"
                :class="{ 'is-invalid': form.errors.has('email') }"
              />
              <has-error :form="form" field="email"></has-error>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
              </div>
              <input
                type="password"
                class="form-control"
                placeholder="New Password"
                name="password"
                v-model="form.password"
                :class="{ 'is-invalid': form.errors.has('password') }"
              />
              <has-error :form="form" field="password"></has-error>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
              </div>
              <input
                type="password"
                class="form-control"
                placeholder="Confirm Password"
                name="confirm_password"
                v-model="form.confirm_password"
                :class="{ 'is-invalid': form.errors.has('confirm_password') }"
              />
              <has-error :form="form" field="confirm_password"></has-error>
            </div>
          </div>
          <div class="modal-footer">
            <button
              v-on:click="closeModal"
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              :disabled="form.busy"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-success"
              v-on:click="updateAccount"
              :disabled="form.busy"
            >
              Save changes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["email", "user_id"],
  data() {
    return {
      form: new Form({
        email: "",
        password: "",
        confirm_password: ""
      })
    };
  },
  methods: {
    closeModal() {
      $("#accountModal").modal("hide");
    },
    updateAccount() {
      this.form
        .post("../../users/" + this.user_id + "/change_password")
        .then(response => {
          this.closeModal();
          toast.fire({
            type: "success",
            title: "Password Successfully Changed"
          });
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  created() {
    this.form.email = this.email;
  }
};
</script>
