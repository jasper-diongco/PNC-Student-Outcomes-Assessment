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
						<button type="button" class="btn btn-primary">
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
	data() {
		return {
			imgPlaceholder: myRootURL + "/images/placeholder.png",
			url: "",
			width: 200,
			height: 200,
			size: 2
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
		}
	}
};
</script>
