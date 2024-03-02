<template>
	<SingleFormHeader :slug="slug" />

	<div class="py-5">
		<div class="container">
			<SingleFormNavigator :slug="slug" />

			<div class="row justify-content-center" v-if="responses.length">
				<div class="col-lg-10">
					<table class="table mt-3">
						<caption>
							Total Responses:
							{{
								responses.length
							}}
						</caption>
						<thead>
							<tr class="text-muted">
								<th>User</th>
								<th
									v-for="question in questions"
									:key="question.id"
								>
									{{ question.name }}
								</th>
							</tr>
						</thead>
						<tbody>
							<tr
								v-for="response in responses"
								:key="response.id"
							>
								<td class="text-primary">
									{{ response.user.email }}
								</td>
								<td
									v-for="(answer, index) in response.answers"
									:key="index"
								>
									{{ answer ?? '' }}
								</td>
								<template
									v-if="
										questions.length >
										Object.values(response.answers).length
									"
								>
									<td
										v-for="i in questions.length -
										Object.values(response.answers).length"
										:key="i"
									>
										-
									</td>
								</template>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { ref } from "vue";
import { doFetch } from "@/utils";
import router from "@/router";
import SingleFormHeader from "@/components/SingleFormHeader.vue";
import SingleFormNavigator from "@/components/SingleFormNavigator.vue";

export default {
	name: "Form Responses",
	props: ["slug"],
	components: {
		SingleFormHeader,
		SingleFormNavigator,
	},
	setup(props) {
		const responses = ref([]);
		const questions = ref([]);
		const getData = async () => {
			const { status, data } = await doFetch(
				"get",
				`forms/${props.slug}/responses`
			);

			const { status: status2, data: data2 } = await doFetch(
				"get",
				`forms/${props.slug}`
			);

			if (status == 404) {
				router.push({ name: "not-found" });
			} else if (status == 403) {
				router.push({ name: "forbidden" });
			} else if (status == 200) {
				responses.value = data.responses;
			}

			if (status2 == 404) {
				router.push({ name: "not-found" });
			} else if (status2 == 403) {
				router.push({ name: "forbidden" });
			} else if (status2 == 200) {
				questions.value = data2.form.questions;
			}
		};

		getData();
		return { responses, questions };
	},
};
</script>
