<template>
	<section class="login">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-6">
					<h1 class="text-center mb-4">Formify</h1>
					<div class="card card-default">
						<div class="card-body">
							<h3 class="mb-3">Login</h3>

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
								<div class="form-group my-3">
									<label for="email" class="mb-1 text-muted"
										>Email Address</label
									>
									<input
										type="email"
										id="email"
										name="email"
										class="form-control"
										autofocus
										v-model="email"
									/>
									<div v-if="errors.email">
										<p
											class="text-danger"
											v-for="(msg, index) in errors.email"
											:key="index"
										>
											{{ msg }}
										</p>
									</div>
								</div>

								<!-- s: input -->
								<div class="form-group my-3">
									<label
										for="password"
										class="mb-1 text-muted"
										>Password</label
									>
									<input
										type="password"
										id="password"
										name="password"
										class="form-control"
										v-model="password"
									/>
									<div v-if="errors.password">
										<p
											class="text-danger"
											v-for="(
												msg, index
											) in errors.password"
											:key="index"
										>
											{{ msg }}
										</p>
									</div>
								</div>

								<div class="mt-4">
									<button
										type="submit"
										class="btn btn-primary"
									>
										Login
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
import { ref } from "vue";
import { doFetch } from "@/utils.js";
import { APP_TOKEN } from "@/config";
import router from "@/router";

export default {
	name: "Login Page",
	setup() {
		const email = ref("");
		const password = ref("");
		const errors = ref({});

		const submit = async () => {
			const { status, data } = await doFetch("post", "auth/login", {
				data: {
					email: email.value,
					password: password.value,
				},
			});

			if (status == 422) {
				errors.value = data.errors;
				errors.value.message = data.message;
			} else if (status == 401) {
				errors.value = {};
				errors.value.message = data.message;
			} else if (status == 200) {
				localStorage.setItem(APP_TOKEN, data.user.accessToken);
				router.push({ name: "home" });
			}
		};

		return { email, password, submit, errors };
	},
};
</script>
