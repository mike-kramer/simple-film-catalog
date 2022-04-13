<template>
    <div class="form-container" @paste="pasteImage">
        <h1>Добавить фильм</h1>
        <el-form :model="filmForm" label-position="top">
            <el-row :gutter="20">
                <el-col :span="8">
                    <el-form-item label="Название">
                        <el-input v-model="filmForm.title"/>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Год">
                        <el-input-number v-model="filmForm.year"/>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Страна">
                        <el-select-v-2 filterable v-model="filmForm.country_id" :options="countries" style="width: 100%;"></el-select-v-2>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="8">
                    <el-form-item label="Источник">
                        <el-input v-model="filmForm.source"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Рейтинг">
                        <el-rate :max="10" v-model="filmForm.rate"></el-rate>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="24">
                    <el-form-item label="Жанры">
                        <el-checkbox-group v-model="filmForm.genres_ids">
                            <el-checkbox v-for="genre in genres" :label="genre.id">{{ genre.title }}</el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                    <el-button type="primary" round size="small" @click="openGenreDia()">Добавить жанр</el-button>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="8">
                    <el-form-item label="Обложка">
                        <el-upload action="" :autoupload="false" :on-change="selectCover" class="cover-uploader" :show-file-list="false" accept="image/*">
                            <img v-if="coverUrl" :src="coverUrl" class="avatar"/>
                            <el-icon v-else class="cover-uploader-icon">
                                <Plus/>
                            </el-icon>
                        </el-upload>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Torrent-файл">
                        <el-upload action="" :autoupload="false" :on-change="selectTorrent" :show-file-list="false">
                            <template #trigger>
                                <el-button type="primary">Выбрать</el-button>
                            </template>
                        </el-upload>
                    </el-form-item>
                    <p v-if="torrentFileName">{{torrentFileName}}</p>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="24">
                    <el-form-item label="Описание" class="editor-container">
                        <ckeditor :editor="editor" v-model="filmForm.description"></ckeditor>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    </div>
    <el-dialog v-model="addGenreVisible" title="Добавить жанр" width="30%">
        <el-form :model="addGenreForm">
            <el-form-item title="Название">
                <el-input v-model="addGenreForm.title"></el-input>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="storeNewGenre()">Добавить</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';


export default {
    data() {
        return {
            filmForm: {
                title: "",
                year: 0,
                rate: 0,
                source: "",
                country_id: "",
                description: "",
                status_id: "",
                genres_ids: []
            },
            addGenreForm: {
                title: ""
            },
            genres: [],
            countries: [],
            statuses: [],
            editor: ClassicEditor,
            addGenreVisible: false,
            coverBlob: null,
            coverUrl: false,
            torrentFileBlob: null,
            torrentFileName: false
        }
    },
    beforeRouteEnter(to, from, next) {
        $axios.post("/graphql", `
            {
                countries {
                    id
                    value
                }
                genres {
                    id
                    title
                }
                statuses {
                    id
                    status_name
                }
            }
        `, {
            headers: {
                "Content-Type": "application/graphql"
            }
        }).then(
            (resp) => next(vm => {
                const data = resp.data.data;
                vm.genres = data.genres;
                vm.countries = data.countries.map(c => ({label: c.value, value: c.id}))
                vm.statuses = data.statuses;
            })
        );
    },
    methods: {
        openGenreDia() {
            this.addGenreForm.title = "";
            this.addGenreVisible = true;
        },
        storeNewGenre() {
            const graphql = `mutation { createGenre(title: "${this.addGenreForm.title}") {id title} }`;
            this.$axios.post("/graphql", graphql, {headers: {"Content-Type": "application/graphql"}}).then(
                () => this.$axios.post("/graphql", "{ genres {id title} }", {headers: {"Content-Type": "application/graphql"}})
            ).then(
                (resp) => {
                    this.genres = resp.data.data.genres;
                    this.addGenreVisible = false;
                }
            );
        },
        pasteImage(evt) {
            const clipboardItems = evt.clipboardData.items;
            const items = [].slice.call(clipboardItems).filter(function (item) {
                // Filter the image items only
                return item.type.indexOf('image') !== -1;
            });
            if (items.length === 0) {
                return;
            }

            const item = items[0];
            this.coverBlob = item.getAsFile();
            this.coverUrl = URL.createObjectURL(this.coverBlob);
        },
        selectCover(uploadFile) {
            this.coverBlob = uploadFile.raw;
            this.coverUrl = URL.createObjectURL(uploadFile.raw);
        },
        selectTorrent(uploadFile) {
            this.torrentFileName = uploadFile.name;
            this.torrentFileBlob = uploadFile.raw;
        }
    }
}
</script>

<style lang="scss" scoped>
.form-container {
    border: 1px solid var(--el-border-color);
    border-radius: var(--el-border-radius-round);
    padding: 10px;

    h1 {
        font-size: var(--el-font-size-extra-large);
    }
}

</style>
<style lang="scss">
.editor-container .el-form-item__content {
    display: block !important;
}

.editor-container .ck-content {
    min-height: 300px;
}

.cover-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: var(--el-transition-duration-fast);
}

.cover-uploader .el-upload:hover {
    border-color: var(--el-color-primary);
}

.cover-uploader img {
    width: 178px;
    height: 178px;
    object-fit: contain;
}

.el-icon.cover-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    text-align: center;
}
</style>

