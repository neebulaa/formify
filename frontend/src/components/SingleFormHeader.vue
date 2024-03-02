<template>
	<div class="hero py-5 bg-light" v-if="form">
		<div class="container text-center">
			<h2 class="mb-2">{{ form.name }}</h2>
			<div class="text-muted mb-4">{{ form.description }}</div>
			<div>
				<div>
					<small>For user domains</small>
				</div>
				<small
					><span class="text-primary">{{
						form.allowed_domains.length
							? form.allowed_domains.join(", ")
							: "All"
					}}</span></small
				>
			</div>
		</div>
	</div>
</template>
<script>
import { ref } from "vue";
import { doFetch } from "@/utils";
import router from "@/router";
export default {
	name: "Single Form Header",
	props: ["slug"],
	setup(props) {
		const form = ref(null);

		const getData = async () => {
			const { status, data } = await doFetch(
				"get",
				`forms/${props.slug}`
			);

			if (status == 404) {
				router.push({ name: "not-found" });
			} else if (status == 403) {
				router.push({ name: "forbidden" });
			} else if (status == 200) {
				form.value = data.form;
			}
		};

		getData();

		return { form };
	},
};
</script>
