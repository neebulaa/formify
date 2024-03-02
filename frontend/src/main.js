import "./assets/css/bootstrap.css";
import "./assets/css/style.css";
import "./assets/js/bootstrap.js";
import "./assets/js/popper.js";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

const app = createApp(App);
app.use(router);
app.mount("#app");
