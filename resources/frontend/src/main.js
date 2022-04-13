import { createApp } from 'vue'
import App from './App.vue'
import {createRouterInstance} from './router'
import ElementPlus from "element-plus";
import 'element-plus/dist/index.css';
import axiosPlugin from "./plugins/axios";
import { createPinia } from 'pinia';
import CKEditor from '@ckeditor/ckeditor5-vue';
import { Plus } from '@element-plus/icons-vue'

const app = createApp(App)

app.use(axiosPlugin);
app.use(createRouterInstance(app));
app.use(ElementPlus);
app.use(createPinia())
app.use(CKEditor);

app.component("Plus", Plus);

app.mount('#app')
