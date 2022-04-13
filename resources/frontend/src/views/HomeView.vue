<template>
    <main>
        <div class="login-box">
            <h2>Каталог просмотренных фильмов</h2>
            <h3>Вход</h3>
            <el-alert type="error" v-if="loginError" title="Неверные данные для входа" />
            <el-form label-position="top" :model="loginForm" ref="loginForm" :rules="rules">
                <el-form-item label="Логин" prop="email">
                    <el-input v-model="loginForm.email"></el-input>
                </el-form-item>
                <el-form-item label="Пароль" prop="password">
                    <el-input v-model="loginForm.password"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button  @click.prevent="login()" type="success">Войти</el-button>
                </el-form-item>
            </el-form>
        </div>
    </main>
</template>

<script>
import { ElLoading } from 'element-plus'

export default {
    data() {
        return {
            loginForm: {
                email: "",
                password: ""
            },
            loginError: false,
            rules: {
                email: [
                    {required: true, message: "Введите логин", trigger: 'blur'}
                ],
                password: [
                    {required: true, message: "Введите пароль", trigger: 'blur'}
                ]
            }
        }
    },
    methods: {
        async login() {
           await this.$refs.loginForm.validate(async (valid, fields) => {
               if (valid) {
                   this.loginError = false;
                   const loading = ElLoading.service({fullscreen: true})
                   try {
                       await this.$axios.get("/sanctum/csrf-cookie").then(() => this.$axios.post("/api/login", this.loginForm));
                       alert("ok");
                   } catch (e) {
                       this.loginError = true;
                   } finally {
                       loading.close();
                   }
               }
           })
        }
    }
}

</script>

<style lang="scss" scoped>

main {
    display: flex;
    position: fixed;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
}

.login-box {
    width: 600px;
    height: 300px;
    padding: 30px;
    box-shadow: var(--el-box-shadow-light);
    border-radius: var(--el-border-radius-round);
    h2 {
        color: #409EFF;
        font-size: var(--el-font-size-extra-large);
        text-align: center;
    }
    h3 {
        color: #67C23A;
        font-size: var(--el-font-size-large);
        text-align: center;
    }
}



</style>
