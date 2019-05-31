<template>
	<div>
		<!-- Button trigger modal -->

		<!-- Modal -->
		<div
			class="modal fade"
			id="imageModal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="exampleModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">
							Add new Image
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
						<div class="d-flex justify-content-center mb-3">
							<img
								:src="url ? url : imgPlaceholder"
								alt="selected image"
								:width="width"
								:height="height"
								style="border:1px solid #ededed;"
							/>
						</div>

						<div class="form-group">
							<label><b>Select File: </b></label>
							<input
								type="file"
								@change="onFileChange"
								accept="image/*"
								ref="file"
							/>
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
							/>
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
							@click="uploadImage"
							type="button"
							class="btn btn-primary"
						>
							Upload
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["testQuestionId"],
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
			formData.append("test_question_id", this.testQuestionId);
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
		closeModal() {
			$("#imageModal").modal("hide");
		}
	}
};
</script>
