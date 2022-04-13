<template>
<main class="common-layout">
    <el-container>
        <el-aside>
            Aside
        </el-aside>
        <el-main>
            <router-view></router-view>
        </el-main>
    </el-container>
</main>
</template>

<script>
export default {
    name: "InternalView"
}
</script>

<style>
.common-layout .el-aside {
    background-color: #d3dce6;
    color: var(--el-text-color-primary);
    text-align: center;
}
.el-form-item__label {
    font-weight: bold !important;
}
</style>
