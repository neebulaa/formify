<template>
	<main v-if="form && user">
		<div class="hero py-5 bg-light">
			<div class="container text-center">
				<h2 class="mb-3">{{ form.name }}</h2>
				<div class="text-muted">
					{{ form.description }}
				</div>
			</div>
		</div>

		<div class="py-5">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-5 col-md-6">
						<div class="text-muted">
							<span class="text-primary">{{ user.email }}</span>
							<small><i>(shared)</i></small>
						</div>

						<form @submit.prevent="submit">
							<div
								class="form-item card card-default my-4"
								:class="{
									'border-danger':
										errors[`answers.${index}.value`],
								}"
								v-for="(question, index) in form.questions"
								:key="question.id"
							>
								<div class="card-body">
									<div class="form-group">
										<label
											:for="`${question.name} - ${question.id}`"
											class="mb-1 text-muted"
											>{{ question.name }}
											<span
												v-if="question.is_required"
												class="text-danger"
												>*</span
											></label
										>
										<input
											v-if="
												question.choice_type ==
												'short answer'
											"
											:id="`${question.name} - ${question.id}`"
											type="text"
											placeholder="Your answer"
											class="form-control"
											v-model="body[question.id].value"
										/>

										<textarea
											v-if="
												question.choice_type ==
												'paragraph'
											"
											:id="`${question.name} - ${question.id}`"
											rows="4"
											placeholder="Your answer"
											class="form-control"
											v-model="body[question.id].value"
										></textarea>

										<input
											v-if="
												question.choice_type == 'date'
											"
											type="date"
											placeholder="Your answer"
											class="form-control"
											:id="`${question.name} - ${question.id}`"
											v-model="body[question.id].value"
										/>

										<div
											v-if="
												question.choice_type ==
												'multiple choice'
											"
										>
											<div
												class="form-check"
												v-for="(
													choice, index
												) in question.choices.split(
													','
												)"
												:key="choice"
											>
												<input
													class="form-check-input"
													type="radio"
													:value="choice"
													:id="`${choice} - ${index}`"
													:name="`${question.name} - ${question.id}`"
													v-model="
														body[question.id].value
													"
												/>
												<label
													class="form-check-label"
													:for="`${choice} - ${index}`"
												>
													{{ choice }}
												</label>
											</div>
										</div>

										<div
											v-if="
												question.choice_type ==
												'checkboxes'
											"
										>
											<div
												class="form-check"
												v-for="(
													choice, index
												) in question.choices.split(
													','
												)"
												:key="choice"
											>
												<input
													class="form-check-input"
													type="checkbox"
													:value="choice"
													:id="`${choice} - ${index}`"
													v-model="
														body[question.id].value
													"
												/>
												<label
													class="form-check-label"
													:for="`${choice} - ${index}`"
												>
													{{ choice }}
												</label>
											</div>
										</div>

										<select
											name="choice_type"
											class="form-select"
											v-if="
												question.choice_type ==
												'dropdown'
											"
											v-model="body[question.id].value"
										>
											<option value="">
												Answer Choices
											</option>
											<option
												:value="choice"
												v-for="choice in question.choices.split(
													','
												)"
												:key="choice"
											>
												{{ choice }}
											</option>
										</select>

										<div
											class="mt-4"
											v-if="
												errors[`answers.${index}.value`]
											"
										>
											<p
												class="text-danger"
												style="font-size: 0.9rem"
												v-for="(msg, index) in errors[
													`answers.${index}.value`
												]"
												:key="index"
											>
												{{ msg }}
											</p>
										</div>
									</div>
								</div>
							</div>

							<div class="mt-4">
								<button class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
</template>
<script>
import { ref } from "vue";
import { doFetch } from "@/utils";
import router from "@/router";

export default {
	name: "Form Responses",
	props: ["slug"],
	setup(props) {
		const form = ref(null);
		const user = ref(null);
		const errors = ref({});
		const body = ref({});

		const getData = async () => {
			const { status, data } = await doFetch(
				"get",
				`forms/${props.slug}/view`
			);

			const { status: userStatus, data: userData } = await doFetch(
				"get",
				"auth/me"
			);

			if (userStatus == 200) {
				user.value = userData.user;
			}

			if (status == 404) {
				router.push({ name: "not-found" });
			} else if (status == 422) {
				router.push({ name: "cant-submit-twice" });
			} else if (status == 403) {
				router.push({ name: "forbidden" });
			} else if (status == 200) {
				form.value = data.form;
				body.value = data.form.questions.reduce(function (acc, curr) {
					if (!acc[curr.id]) {
						acc[curr.id] = {
							question_id: curr.id,
							value: curr.choice_type == "checkboxes" ? [] : "",
						};
					}
					return acc;
				}, {});
			}
		};

		getData();

		const submit = async () => {
			console.log(
				Object.values(body.value).map((e) =>
					Array.isArray(e.value) ? e.value.join(",") : e
				)
			);
			const { status, data } = await doFetch(
				"post",
				`forms/${props.slug}/responses`,
				{
					data: {
						answers: Object.values(body.value).map((e) =>
							Array.isArray(e.value)
								? { ...e, value: e.value.join(",") }
								: e
						),
					},
				}
			);
			if (status == 422) {
				errors.value = data.errors;
				errors.value.message = data.message;
				console.log(errors.value);
			} else if (status == 200) {
				router.push({ name: "response-submitted" });
			}
		};

		return { form, user, submit, body, errors };
	},
};
</script>
