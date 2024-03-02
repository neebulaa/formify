<template>
	<nav class="navbar navbar-expand-lg sticky-top bg-primary navbar-dark">
		<div class="container">
			<router-link :to="{ name: 'home' }" class="navbar-brand"
				>Formify</router-link
			>
			<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<router-link
						:to="{ name: 'home' }"
						class="nav-link active"
						>{{ user.name }}</router-link
					>
				</li>
				<li class="nav-item">
					<button
						class="btn bg-white text-primary ms-4"
						@click="logout"
					>
						Logout
					</button>
				</li>
			</ul>
		</div>
	</nav>

	<main>
		<router-view />
	</main>
</template>

<script>
import { ref } from "vue";
import { doFetch } from "@/utils";
import router from "@/router";
import { APP_TOKEN } from "@/config";
export default {
	name: "Authenticated Page",
	setup() {
		const user = ref({});
		const getData = async () => {
			const { data } = await doFetch("get", "auth/me");
			user.value = data.user;
		};

		const logout = async function () {
			const { status } = await doFetch("post", "auth/logout");
			if (status == 200) {
				localStorage.removeItem(APP_TOKEN);
				router.push({ name: "login" });
			}
		};

		getData();

		return { user, logout };
	},
};
</script>
