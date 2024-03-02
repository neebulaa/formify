<template>
	<SingleFormHeader :slug="slug" />
	<div class="py-5" v-if="form">
		<div class="container">
			<SingleFormNavigator :slug="slug" />

			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-6">
					<!-- all questions item -->
					<div
						class="question-item card card-default my-4"
						v-for="question in form.questions"
						:key="question.id"
					>
						<div class="card-body">
							<div class="form-group my-3">
								<input
									type="text"
									placeholder="Question"
									class="form-control"
									name="name"
									:value="question.name"
									disabled
								/>
							</div>

							<div class="form-group my-3">
								<select
									name="choice_type"
									class="form-select"
									disabled
								>
									<option>Choice Type</option>
									<option
										value="short answer"
										:selected="
											question.choice_type ==
											'short answer'
										"
									>
										Short Answer
									</option>
									<option
										value="paragraph"
										:selected="
											question.choice_type == 'paragraph'
										"
									>
										Paragraph
									</option>
									<option
										value="date"
										:selected="
											question.choice_type == 'date'
										"
									>
										Date
									</option>
									<option
										value="multiple choice"
										:selected="
											question.choice_type ==
											'multiple choice'
										"
									>
										Multiple Choice
									</option>
									<option
										value="dropdown"
										:selected="
											question.choice_type == 'dropdown'
										"
									>
										Dropdown
									</option>
									<option
										value="checkboxes"
										:selected="
											question.choice_type == 'checkboxes'
										"
									>
										Checkboxes
									</option>
								</select>
							</div>

							<div
								class="form-group my-3"
								v-if="
									requiredChoices.includes(
										question.choice_type
									)
								"
							>
								<textarea
									placeholder="Choices"
									class="form-control"
									name="choices"
									rows="4"
									disabled
									v-model="question.choices"
								></textarea>
								<div class="form-text">
									Separate choices using comma ",".
								</div>
							</div>

							<div
								class="form-check form-switch"
								aria-colspan="my-3"
							>
								<input
									class="form-check-input"
									type="checkbox"
									role="switch"
									id="required"
									disabled
									:checked="question.is_required"
								/>
								<label class="form-check-label" for="required"
									>Required</label
								>
							</div>
							<div class="mt-3">
								<button
									type="submit"
									class="btn btn-outline-danger"
									@click="deleteQuestion(question)"
								>
									Remove
								</button>
							</div>
						</div>
					</div>

					<div class="question-item card card-default my-4">
						<div class="card-body">
							<form @submit.prevent="addQuestion">
								<div class="form-group my-3">
									<input
										type="text"
										placeholder="Question"
										class="form-control"
										name="name"
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

								<div class="form-group my-3">
									<select
										name="choice_type"
										class="form-select"
										v-model="choice_type"
									>
										<option selected>Choice Type</option>
										<option value="short answer">
											Short Answer
										</option>
										<option value="paragraph">
											Paragraph
										</option>
										<option value="date">Date</option>
										<option value="multiple choice">
											Multiple Choice
										</option>
										<option value="dropdown">
											Dropdown
										</option>
										<option value="checkboxes">
											Checkboxes
										</option>
									</select>
									<div v-if="errors.choice_type">
										<p
											class="text-danger"
											v-for="(
												msg, index
											) in errors.choice_type"
											:key="index"
										>
											{{ msg }}
										</p>
									</div>
								</div>

								<div
									class="form-group my-3"
									v-if="requiredChoices.includes(choice_type)"
								>
									<textarea
										placeholder="Choices"
										class="form-control"
										name="choices"
										rows="4"
										v-model="choices"
									></textarea>
									<div class="form-text">
										Separate choices using comma ",".
									</div>
									<div v-if="errors.choices">
										<p
											class="text-danger"
											v-for="(
												msg, index
											) in errors.choices"
											:key="index"
										>
											{{ msg }}
										</p>
									</div>
								</div>

								<div
									class="form-check form-switch"
									aria-colspan="my-3"
								>
									<input
										class="form-check-input"
										type="checkbox"
										role="switch"
										id="required"
										v-model="is_required"
									/>
									<label
										class="form-check-label"
										for="required"
										>Required</label
									>
								</div>
								<div class="mt-3">
									<button
										type="submit"
										class="btn btn-outline-primary"
									>
										Save
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { doFetch } from "@/utils";
import { ref } from "vue";
import router from "@/router";
import { APP_URL } from "@/config";
import SingleFormHeader from "@/components/SingleFormHeader.vue";
import SingleFormNavigator from "@/components/SingleFormNavigator.vue";
export default {
	name: "Single Form Page",
	components: {
		SingleFormHeader,
		SingleFormNavigator,
	},
	props: ["slug"],
	setup(props) {
		const form = ref(null);
		const requiredChoices = ["multiple choice", "dropdown", "checkboxes"];

		// question
		const name = ref("");
		const choice_type = ref("short answer");
		const choices = ref("");
		const is_required = ref(true);
		const errors = ref({});

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

		const addQuestion = async () => {
			const { status, data } = await doFetch(
				"post",
				`forms/${props.slug}/questions`,
				{
					data: {
						name: name.value,
						choice_type: choice_type.value,
						choices:
							choices.value == ""
								? []
								: choices.value.split(",").map((e) => e.trim()),
						is_required: is_required.value,
					},
				}
			);

			if (status == 422) {
				errors.value = data.errors;
				errors.value.message = data.message;
			} else if (status == 200) {
				errors.value = {};
				form.value.questions.push(data.question);
			}
		};

		const deleteQuestion = async (question) => {
			const confirmation = confirm("Delete this question?");
			if (!confirmation) {
				return;
			}

			const { status, data } = await doFetch(
				"delete",
				`forms/${props.slug}/questions/${question.id}`,
				{
					data: {
						name: name.value,
						choice_type: choice_type.value,
						choices: choices.value,
						is_required: is_required.value,
					},
				}
			);

			if (status == 200) {
				form.value.questions = form.value.questions.filter(
					(q) => q != question
				);
			}
		};

		getData();
		return {
			form,
			addQuestion,
			deleteQuestion,
			requiredChoices,
			is_required,
			errors,
			name,
			choice_type,
			choices,
		};
	},
};
</script>
