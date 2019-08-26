<template>
	<div>
		<!-- Button trigger modal -->

		<!-- Modal -->
		<div
			class="modal fade"
			:id="isUpdate ? 'imageModalUpdate' : 'imageModal'"
			tabindex="-1"
			role="dialog"
			aria-labelledby="exampleModalLabel"
			aria-hidden="true"
			style="z-index: 1400;"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">
							{{ modalTitle }}
							<i class="fa fa-image text-primary"></i>
						</h5>
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
						<div v-if="isUpdate" class="d-flex justify-content-end">
							<button @click="getImageObject" class="btn btn-sm">
								<i class="fa fa-redo-alt"></i>
							</button>
						</div>
						<div class="d-flex justify-content-center mb-3">
							<img
								:src="url ? url : imgPlaceholder"
								alt="selected image"
								:width="width"
								:height="height"
								style="border:1px solid #ededed;"
							/>
						</div>

						<div v-if="!isUpdate" class="form-group">
							<label><b>Select File: </b></label>
							<input
								type="file"
								@change="onFileChange"
								accept="image/*"
								ref="file"
								name="file"
								data-vv-name="file"
								v-validate="'required'"
								:class="{ 'is-invalid': errors.has('file') }"
							/>
							<div class="text-warning">
								{{ errors.first("file") }}
							</div>
						</div>

						<!-- <progress
							max="100"
							:value.prop="uploadPercentage"
						></progress> -->

						<div class="progress" v-if="showProgress">
							<div
								class="progress-bar"
								role="progressbar"
								:style="{ width: uploadPercentage + '%' }"
								aria-valuenow="25"
								aria-valuemin="0"
								aria-valuemax="100"
							>
								{{ uploadPercentage }}%
							</div>
						</div>

						<div class="form-group">
							<label><b>Description:</b></label>
							<input
								type="text"
								v-model="description"
								class="form-control"
								placeholder="Enter Description"
								name="description"
								data-vv-name="description"
								v-validate="'required'"
								:class="{
									'is-invalid': errors.has('description')
								}"
							/>
							<div class="invalid-feedback">
								{{ errors.first("description") }}
							</div>
						</div>

						<div>
							<label><b>Size:</b></label>
							<select
								v-model="size"
								@change="onSizeChange"
								class="form-control"
							>
								<option value="1">Small</option>
								<option value="2">Medium</option>
								<option value="3">Large</option>
								<option value="4">Small - x</option>
								<option value="5">Medium - x</option>
								<option value="6">Large - x</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button
							type="button"
							class="btn btn-secondary"
							data-dismiss="modal"
						>
							Close
						</button>
						<button
							@click="saveImage"
							type="button"
							class="btn btn-primary"
						>
							{{ btnName }}
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["testQuestionId", "refId", "isUpdate", "isDirect", "id"],
	data() {
		return {
			imgPlaceholder: myRootURL + "/images/placeholder.png",
			url: "",
			width: 200,
			height: 200,
			size: 2,
			file: "",
			description: "",
			uploadPercentage: 0,
			showProgress: false
		};
	},
	computed: {
		modalTitle() {
			return this.isUpdate ? "View Image" : "Add new Image";
		},
		btnName() {
			return this.isUpdate ? "Save" : "Upload";
		}
	},
	watch: {
		id() {
			this.getImageObject();
		}
	},
	methods: {
		onFileChange(e) {
			const file = e.target.files[0];
			this.url = URL.createObjectURL(file);
		},
		onSizeChange() {
			if (this.size == 1) {
				this.width = 150;
				this.height = 150;
			} else if (this.size == 2) {
				this.width = 200;
				this.height = 200;
			} else if (this.size == 3) {
				this.width = 270;
				this.height = 270;
			} else if (this.size == 4) {
				this.width = 300;
				this.height = 150;
			} else if (this.size == 5) {
				this.width = 350;
				this.height = 200;
			} else if (this.size == 6) {
				this.width = 450;
				this.height = 270;
			}
		},
		uploadImage() {
			this.file = this.$refs.file.files[0];

			let formData = new FormData();
			formData.append("image", this.file);
			formData.append("description", this.description);
			formData.append("size", this.size);
			formData.append("width", this.width);
			formData.append("height", this.height);
			formData.append("ref_id", this.refId);

			this.showProgress = true;
			ApiClient.post("/image_objects", formData, {
				headers: {
					"Content-Type": "multipart/form-data"
				},
				onUploadProgress: progressEvent => {
					this.uploadPercentage = parseInt(
						Math.round(
							(progressEvent.loaded * 100) / progressEvent.total
						)
					);
				}
			})
				.then(response => {
					toast.fire({
						title: "Image successfully uploaded!",
						type: "success"
					});
					this.closeModal();
					this.$emit("objects-added");
				})
				.catch(response => {
					alert("Failed to upload the image!");
				});
		},
		updateImage() {
			ApiClient.put("/image_objects/" + this.id, {
				description: this.description,
				width: this.width,
				height: this.height,
				size: this.size
			})
				.then(response => {
					toast.fire({
						title: "Image successfully updated!",
						type: "success"
					});
					this.closeModal();
					this.$emit("objects-added");
					this.file = "";
					this.url = "";
				})
				.catch(response => {
					alert("Failed to upload the image!");
				});
		},
		saveImage() {
			this.$validator.validateAll().then(isValid => {
				if (isValid) {
					if (this.isUpdate) {
						this.updateImage();
					} else {
						this.uploadImage();
					}
				} else {
					toast.fire({
						type: "error",
						title: "Please enter a valid data!"
					});
				}
			});
		},
		getImageObject() {
			ApiClient.get("/image_objects/" + this.id).then(response => {
				this.url = myRootURL + "/storage/" + response.data.path;
				this.description = response.data.description;
				this.width = response.data.width;
				this.height = response.data.height;
				this.size = response.data.size;
			});
		},
		closeModal() {
			if (this.isUpdate) {
				$("#imageModalUpdate").modal("hide");
			} else {
				$("#imageModal").modal("hide");
			}
		}
	}
};
</script>
