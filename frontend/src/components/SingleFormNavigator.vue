<template>
	<div class="row justify-content-center">
		<div class="col-lg-5 col-md-6">
			<div class="input-group mb-5">
				<input
					type="text"
					class="form-control form-link"
					readonly
					v-model="viewUrl"
				/>
				<button @click="copy" class="btn btn-primary">Copy</button>
			</div>

			<ul class="nav nav-tabs mb-2 justify-content-center">
				<li class="nav-item">
					<router-link
						:to="{
							name: 'single-form',
							params: { slug: slug },
						}"
						class="nav-link"
						:class="{ active: $route.fullPath == `/forms/${slug}` }"
						>Questions</router-link
					>
				</li>
				<li class="nav-item">
					<router-link
						class="nav-link"
						:class="{
							active:
								$route.fullPath == `/forms/${slug}/responses`,
						}"
						:to="{
							name: 'form-response',
							params: { slug: slug },
						}"
						>Responses</router-link
					>
				</li>
			</ul>
		</div>
	</div>
</template>
<script>
import { ref } from "vue";
import { APP_URL } from "@/config";
export default {
	name: "Single Form Navigator",
	props: ["slug"],
	setup(props) {
		const viewUrl = ref(`${APP_URL}/forms/${props.slug}/view`);
		const copy = () => {
			navigator.clipboard.writeText(viewUrl.value);
		};

		return { viewUrl, copy };
	},
};
</script>
