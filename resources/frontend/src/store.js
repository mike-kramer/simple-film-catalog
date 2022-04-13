import {defineStore} from "pinia";

export const UserStore = defineStore("UserStore", {
    state: () => {
        return {
            user: null
        }
    },
    actions: {
        async loadUser($axios) {
            try {
                const resp = await $axios.get("/api/user");
                console.log(resp);
                this.user = resp.data;
            } catch (e) {
                this.user = null;
            }
        },
    }
})
