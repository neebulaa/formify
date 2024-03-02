<template>
	<div class="hero py-5 bg-light">
		<div class="container">
			<h2>Create Form</h2>
		</div>
	</div>

	<div class="py-5">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-4">
					<div
						class="alert alert-danger alert-dismissible fade show"
						role="alert"
						v-if="errors.message"
					>
						{{ errors.message }}
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="alert"
							aria-label="Close"
						></button>
					</div>

					<form @submit.prevent="submit">
						<!-- s: input -->
						<div class="form-group mb-3">
							<label for="name" class="mb-1 text-muted"
								>Form Name</label
							>
							<input
								type="text"
								id="name"
								name="name"
								class="form-control"
								autofocus
								v-model="name"
							/>
							<div v-if="errors.name">
								<p
									class="text-danger"
									v-for="(msg, index) in errors.name"
									:key="index"
								>
									{{ msg }}
								</p>
							</div>
						</div>

						<!-- s: input -->
						<div class="form-group my-3">
							<label for="slug" class="mb-1 text-muted"
								>Form Slug</label
							>
							<input
								type="text"
								id="slug"
								name="slug"
								class="form-control"
								v-model="slug"
							/>
							<div v-if="errors.slug">
								<p
									class="text-danger"
									v-for="(msg, index) in errors.slug"
									:key="index"
								>
									{{ msg }}
								</p>
							</div>
						</div>

						<!-- s: input -->
						<div class="form-group my-3">
							<label for="description" class="mb-1 text-muted"
								>Description</label
							>
							<textarea
								id="description"
								name="description"
								rows="4"
								class="form-control"
								v-model="description"
							></textarea>
							<div v-if="errors.description">
								<p
									class="text-danger"
									v-for="(msg, index) in errors.description"
									:key="index"
								>
									{{ msg }}
								</p>
							</div>
						</div>

						<!-- s: input -->
						<div class="form-group my-3">
							<label for="allowed-domains" class="mb-1 text-muted"
								>Allowed Domains</label
							>
							<input
								type="text"
								id="allowed-domains"
								name="allowed_domains"
								class="form-control"
								v-model="allowedDomains"
							/>
							<div class="form-text">
								Separate domains using comma ",". Ignore for
								public access.
							</div>
							<div v-if="errors.allowed_domains">
								<p
									class="text-danger"
									v-for="(
										msg, index
									) in errors.allowed_domains"
									:key="index"
								>
									{{ msg }}
								</p>
							</div>
						</div>

						<!-- s: input -->
						<div class="form-check form-switch" aria-colspan="my-3">
							<input
								type="checkbox"
								id="limit_one_response"
								name="limit_one_response"
								class="form-check-input"
								role="switch"
								v-model="limitOneResponse"
							/>
							<label
								class="form-check-label"
								for="limit_one_response"
								>Limit to 1 response</label
							>
							<div v-if="errors.limit_one_response">
								<p
									class="text-danger"
									v-for="(
										msg, index
									) in errors.limit_one_response"
									:key="index"
								>
									{{ msg }}
								</p>
							</div>
						</div>

						<div class="mt-4">
							<button type="submit" class="btn btn-primary">
								Save
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { ref } from "vue";
import { doFetch } from "@/utils";
import router from "@/router";

export default {
	name: "Create Form Page",
	setup() {
		const name = ref("");
		const slug = ref("");
		const description = ref("");
		const allowedDomains = ref("");
		const limitOneResponse = ref(false);

		const errors = ref({});

		const submit = async () => {
			const { status, data } = await doFetch("post", "forms", {
				data: {
					name: name.value,
					slug: slug.value,
					description: description.value,
					limit_one_response: limitOneResponse.value,
					allowed_domains:
						allowedDomains == ""
							? null
							: allowedDomains.value.split(","),
				},
			});

			if (status == 422) {
				errors.value.message = data.message;
				errors.value = data.errors;
			} else if (status == 200) {
				router.push({ name: "home" });
			}
		};

		return {
			name,
			slug,
			description,
			allowedDomains,
			limitOneResponse,
			submit,
			errors,
		};
	},
};
</script>
