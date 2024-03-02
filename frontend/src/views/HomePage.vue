<template>
	<div class="hero py-5 bg-light">
		<div class="container">
			<router-link :to="{ name: 'create-form' }" class="btn btn-primary"
				>Create Form</router-link
			>
		</div>
	</div>

	<div class="list-form py-5">
		<div class="container">
			<h6 class="mb-3">List Form</h6>
			<h2 v-if="forms.length == 0">No forms yet</h2>
			<router-link
				:to="{
					name: 'single-form',
					params: { slug: form.slug },
				}"
				class="card card-default mb-3"
				v-for="form in forms"
				:key="form.id"
			>
				<div class="card-body">
					<h5 class="mb-1">{{ form.name }}</h5>
					<small class="text-muted"
						>@{{ form.slug }}
						{{
							form.limit_one_response
								? "(limit for 1 response)"
								: ""
						}}</small
					>
				</div>
			</router-link>
		</div>
	</div>
</template>
<script>
import { doFetch } from "@/utils";
import { ref } from "vue";
export default {
	name: "Home Page",
	setup() {
		const forms = ref([]);
		const getData = async () => {
			const { data } = await doFetch("get", "forms");
			forms.value = data.forms;
		};
		getData();
		return { forms };
	},
};
</script>
