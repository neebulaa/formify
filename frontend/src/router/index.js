import { createRouter, createWebHistory } from "vue-router";
import AuthPage from "../views/AuthPage.vue";
import LoginPage from "../views/LoginPage.vue";
import HomePage from "../views/HomePage.vue";
import CreateFormPage from "../views/CreateFormPage.vue";
import NotFoundPage from "../views/NotFoundPage.vue";
import SingleFormPage from "../views/SingleFormPage.vue";
import ForbiddenPage from "../views/ForbiddenPage.vue";
import SubmitResponsePage from "../views/SubmitResponsePage.vue";
import FormResponsePage from "../views/FormResponsePage.vue";
import ResponseSubmittedPage from "../views/ResponseSubmittedPage.vue";
import CantSubmitTwicePage from "../views/CantSubmitTwicePage.vue";
import { doFetch } from "@/utils.js";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "/login",
			name: "login",
			component: LoginPage,
			meta: {
				guest: true,
			},
		},
		{
			path: "/",
			name: "auth-page",
			component: AuthPage,
			meta: {
				auth: true,
			},
			children: [
				{
					path: "/",
					name: "home",
					component: HomePage,
				},
				{
					path: "/create-form",
					name: "create-form",
					component: CreateFormPage,
				},
				{
					path: "/forms/:slug",
					name: "single-form",
					component: SingleFormPage,
					props: true,
				},
				{
					path: "/forms/:slug/responses",
					name: "form-response",
					component: FormResponsePage,
					props: true,
				},
			],
		},
		{
			path: "/forms/:slug/view",
			name: "submit-response",
			component: SubmitResponsePage,
			meta: {
				auth: true,
			},
			props: true,
		},
		{
			path: "/forbidden",
			name: "forbidden",
			component: ForbiddenPage,
			meta: {
				auth: true,
			},
		},
		{
			path: "/response-submitted",
			name: "response-submitted",
			component: ResponseSubmittedPage,
			meta: {
				auth: true,
			},
		},
		{
			path: "/cant-submit-twice",
			name: "cant-submit-twice",
			component: CantSubmitTwicePage,
			meta: {
				auth: true,
			},
		},
		{
			path: "/:catchAll(.*)*",
			name: "not-found",
			component: NotFoundPage,
			meta: {
				auth: true,
			},
		},
	],
});

router.beforeEach(async function (to, from, next) {
	if (to.meta.auth) {
		const { status } = await doFetch("get", "auth/me");
		if (status == 401) {
			return next({ name: "login" });
		}
	}

	if (to.meta.guest) {
		const { status } = await doFetch("get", "auth/me");
		if (status == 200) {
			return next({ name: "home" });
		}
	}

	return next();
});

export default router;
