import { APP_TOKEN, API_URL } from "@/config.js";
import axios from "axios";

const doFetch = async (method, url, options = {}) => {
	const headers = {
		"Content-Type": "application/json",
		Accept: "application/json",
	};

	const token = localStorage.getItem(APP_TOKEN);
	headers["Authorization"] = `Bearer ${token}`;
	options.headers = headers;

	try {
		const response = await axios({
			url: `${API_URL}/${url}`,
			method,
			...options,
		});
		return response;
	} catch (err) {
		return err.response;
	}
};

export { doFetch };
