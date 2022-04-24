<template>
    <div class="form-container" @paste="pasteImage">
        <h1 v-if="!edit">Добавить фильм</h1>
        <h1 v-if="edit">Редактировать фильм</h1>

        <el-tabs v-model="activeTab" type="card">
            <el-tab-pane label="Основные данные" name="commonData">
                <el-form :model="filmForm" :rules="rules" label-position="top" ref="filmForm">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Тип *" prop="type">
                                <el-select v-model="filmForm.type">
                                    <el-option v-for="t in types" :key="t.value" :label="t.label" :value="t.value"/>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Название *" prop="title">
                                <el-input v-model="filmForm.title"/>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Год *" prop="year">
                                <el-input-number v-model="filmForm.year"/>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Страна *" prop="country_id">
                                <el-select-v-2 filterable v-model="filmForm.country_id" :options="countries" style="width: 100%;"></el-select-v-2>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Источник *" prop="source">
                                <el-input v-model="filmForm.source"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Статус *" prop="status_id">
                                <el-select-v-2 filterable v-model="filmForm.status_id" :options="statuses" style="width: 100%;"></el-select-v-2>
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
                            <el-form-item label="Жанры" prop="genres_ids">
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
                                <el-upload action="" :auto-upload="false" :on-change="selectCover" class="cover-uploader" :show-file-list="false" accept="image/*">
                                    <img v-if="coverUrl" :src="coverUrl" class="avatar"/>
                                    <el-icon v-else class="cover-uploader-icon">
                                        <Plus/>
                                    </el-icon>
                                </el-upload>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Torrent-файл">
                                <el-upload action="" :auto-upload="false" :on-change="selectTorrent" :show-file-list="false">
                                    <template #trigger>
                                        <el-button type="primary">Выбрать</el-button>
                                    </template>
                                </el-upload>
                            </el-form-item>
                            <p v-if="torrentFileName">
                                <a :href="torrentUrl">{{ torrentFileBaseName }}</a>
                            </p>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="24">
                            <el-form-item label="Описание *" class="editor-container" prop="description" v-if="showCkEditor">
                                <ckeditor :editor="editor" v-model="filmForm.description"></ckeditor>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="24">
                            <el-button type="success" @click="saveFilm()">Сохранить</el-button>
                        </el-col>
                    </el-row>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="Сезоны" name="seasons" v-if="$route.params.id && filmForm.type == 'series'">
                <h1>Сезоны</h1>
                <el-button style="margin: 20px;" @click="addSeason">Добавить</el-button>
                <el-table :data="seasons" style="width: 100%;">
                    <el-table-column prop="number" label="Номер"></el-table-column>
                    <el-table-column prop="type" label="Тип"></el-table-column>
                    <el-table-column label="Эпизоды">
                        <template #default="{row}">
                            {{ row.total_episodes_now }} / {{ row.total_episodes }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="start_date" label="Начинается/начался"></el-table-column>
                    <el-table-column label="Действия">
                        <template #default="{row}">
                            <el-button type="primary" :icon="Edit" circle @click="editSeason(row)"/>
                            <el-button type="danger" :icon="Delete" circle/>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
        </el-tabs>
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
    <el-dialog v-model="seasonDiaOpened" title="Добавить/редактировать сезон">
        <el-form :model="seasonForm" width="60%" label-position="top" :rules="seasonFormRules" ref="seasonForm">
            <el-row :gutter="20">
                <el-col :span="12">
                    <el-form-item label="Номер" prop="number">
                        <el-input-number v-model="seasonForm.number"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Тип">
                        <el-select v-model="seasonForm.type">
                            <el-option v-for="type in Object.keys(seasonTypes)" :key="type" :value="type" :label="seasonTypes[type]"/>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Общее количество эпизодов">
                        <el-input-number v-model="seasonForm.total_episodes"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Эпизодов сейчас">
                        <el-input-number v-model="seasonForm.total_episodes_now"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Дата старта">
                        <el-date-picker type="date" value-format="YYYY-MM-DD" v-model="seasonForm.start_date"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="saveSeason()">Сохранить</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {ElLoading, ElNotification} from "element-plus";
import {
    Delete,
    Edit,
} from '@element-plus/icons-vue'

function basename(path) {
    const parts = path.split('/');
    return parts[parts.length - 1];
}


let formInitialization = {
    title: "",
    type: "film",
    year: 0,
    rate: 0,
    source: "",
    country_id: "",
    description: "",
    status_id: "",
    genres_ids: []
};

const seasonFormInitialization = {
    number: 1,
    type: "future",
    total_episodes: 0,
    total_episodes_now: 0,
    start_date: ""
};

export default {
    setup() {
        return {
            Edit, Delete
        }
    },
    data() {

        return {

            filmForm: Object.assign({}, formInitialization),
            storagePath: location.href.includes('localhost') ? "http://localhost:24000/storage" : "/storage",
            rules: {
                title: [
                    {required: true, message: "Введите название", trigger: "blur"},
                    {max: 500, message: "Максимальная длина 500 символов", trigger: "blur"},
                ],
                source: [
                    {required: true, message: "Введите источник", trigger: "blur"},
                    {max: 255, message: "Максимальная длина 255 символов", trigger: "blur"},
                ],
                year: [
                    {required: true, message: "Введите год", trigger: "blur"},
                    {min: 1900, message: "Минимальный год 1900", trigger: "blur", type: "number"},
                ],
                description: [
                    {required: true, message: "Введите описание", trigger: "blur"},
                ],
                country_id: [
                    {required: true, message: "Выберете страну", trigger: "change"},
                ],
                generes_ids: [
                    {min: 1, message: "Выберете хотя бы один жанр", trigger: "change"},
                ],
                type: [
                    {required: true, message: "Выберете тип", trigger: "change"},
                ],
                status_id: [
                    {required: true, message: "Выберете статус", trigger: "change"},
                ]
            },
            addGenreForm: {
                title: ""
            },
            seasonForm: Object.assign({}, seasonFormInitialization),
            seasonFormRules: {
                number: [
                    {
                        validator: (rule, value, callback) => {
                            return this.seasons.filter(s => this.editingSeasonId ? this.editingSeasonId != s.id : true).filter((s) => {
                                return s.number == value;
                            }).length === 0
                        },
                        message: "Сезон с таким номером уже добавлен",
                        trigger: "blur"
                    },

                ]
            },
            seasonDiaOpened: false,
            editingSeasonId: false,
            edit: false,
            genres: [],
            countries: [],
            statuses: [],
            editor: ClassicEditor,
            addGenreVisible: false,
            coverBlob: null,
            coverUrl: false,
            torrentFileBlob: null,
            torrentFileName: false,
            showCkEditor: false,
            seasons: [],
            types: [
                {
                    value: "film",
                    label: "Фильм"
                },
                {
                    value: "series",
                    label: "Сериал"
                }
            ],
            activeTab: "commonData",
            seasonTypes: {
                future: "Не начался",
                onGoing: "Онгоинг",
                finished: "Завершён"
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        const commonDataQuery = `countries {
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
                }`;
        const filmDataQuery = to.params.id ?
            `film( id: ${to.params.id}) {
                id title year rate cover source torrent_file country_id rate
                description status_id type genres { id title }
            }
            seasons(film_id: ${to.params.id}) {
                id film_id number type total_episodes total_episodes_now start_date
            }
            `
            : '';
        const loading = ElLoading.service({fullscreen: true});
        $axios.post("/graphql", `
            {
                ${commonDataQuery}
                ${filmDataQuery}
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
                vm.statuses = data.statuses.map(c => ({label: c.status_name, value: c.id}));
                vm.edit = !!to.params.id;
                if (to.params.id) {
                    vm.updateFilmData(data.film, data.seasons);
                }
                setTimeout(() => vm.showCkEditor = true, 40);
            })
        ).finally(() => loading.close())
    },
    async beforeRouteUpdate(to, from) {
        this.filmForm = Object.assign({}, formInitialization);
        if (to.params.id) {
            this.edit = !!to.params.id;
            let data = (await this.$axios.post("/graphql", `{film( id: ${to.params.id}) {
                id title year rate cover source torrent_file country_id rate
                description status_id type genres { id title }
            }  seasons(film_id: ${to.params.id}) {
                id film_id number type total_episodes total_episodes_now start_date
            }}`, {
                headers: {
                    "Content-Type": "application/graphql"
                }
            })).data.data;
            this.showCkEditor = false;
            this.updateFilmData(data.film, data.seasons);
            setTimeout(() => vm.showCkEditor = true, 40);
        }

    },
    computed: {
        torrentFileBaseName() {
            return basename(this.torrentFileName);
        },
        torrentUrl() {
            return this.torrentFileBlob ? URL.createObjectURL(this.torrentFileBlob) : `${this.storagePath}/${this.torrentFileName}`
        },
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
        },
        async saveFilm() {
            await this.$refs.filmForm.validate(async valid => {
                if (!valid) {
                    return;
                }
                let fd = new FormData;

                for (let field in this.filmForm) {
                    if (!Array.isArray(this.filmForm[field])) {
                        fd.append(field, this.filmForm[field])
                    } else {
                        this.filmForm[field].forEach(v => fd.append(field + "[]", v))
                    }
                }
                if (this.coverBlob) {
                    fd.append("cover", this.coverBlob);
                }
                if (this.torrentFileBlob) {
                    fd.append("torrent", this.torrentFileBlob);
                }

                const loading = ElLoading.service({fullscreen: true});

                try {
                    const res = await this.$axios.post("/api/film/save" + (this.$route.params.id ? `/${this.$route.params.id}` : ""), fd);
                    ElNotification({
                        title: 'Сохранение',
                        message: 'Фильм успешно сохранён',
                        type: 'success',
                    });
                    if (!this.$route.params.id) {
                        this.$router.push(`/film/edit/${res.data.id}`);
                    }
                } finally {
                    loading.close();
                }
            })

        },
        updateFilmData(filmData, seasonsData) {
            const filmFormData = Object.assign({}, formInitialization)
            for (let f in filmFormData) {
                if (filmData[f]) {
                    filmFormData[f] = filmData[f];
                }
            }
            filmFormData.genres_ids = filmData.genres.map(g => g.id);
            this.filmForm = filmFormData;
            if (filmData.cover) {
                this.coverUrl = `${this.storagePath}/${filmData.cover}`;
            }
            if (filmData.torrent_file) {
                this.torrentFileName = filmData.torrent_file;
            }
            this.seasons = seasonsData ? seasonsData : [];
        },
        addSeason() {
            const nextNumber = this.seasons.length === 0 ? 0 : Math.max(...this.seasons.map(s => s.number)) + 1;
            this.seasonForm = Object.assign({}, seasonFormInitialization, {number: nextNumber});
            this.editingSeasonId = false;
            this.seasonDiaOpened = true;
        },
        editSeason(season) {
            this.seasonForm = Object.assign({}, season);
            this.editingSeasonId = season.id;
            this.seasonDiaOpened = true;
        },
        async saveSeason() {
            await this.$refs.seasonForm.validate( valid => {
                if (!valid) {
                    return;
                }
                const createGraphQL = `mutation {
                    createSeason(
                        film_id: ${this.$route.params.id}, number: ${this.seasonForm.number}, type: "${this.seasonForm.type}", total_episodes: ${this.seasonForm.total_episodes},
                        total_episodes_now: ${this.seasonForm.total_episodes_now}, start_date: "${this.seasonForm.start_date}"
                    ) { id }
                }`;
                const updateGraphQL = `mutation {
                    updateSeason(
                        id: ${this.editingSeasonId},
                        film_id: ${this.$route.params.id}, number: ${this.seasonForm.number}, type: "${this.seasonForm.type}", total_episodes: ${this.seasonForm.total_episodes},
                        total_episodes_now: ${this.seasonForm.total_episodes_now}, start_date: "${this.seasonForm.start_date}"
                    ) { id }
                }`;
                const loading = ElLoading.service({fullscreen: true});
                this.$axios.post("/graphql", this.editingSeasonId ? updateGraphQL : createGraphQL, {headers: {"Content-Type": "application/graphql"}}).then(
                    (resp) => {
                        if (resp.data.errors) {
                            throw resp.data.errors;
                        }
                        return this.$axios.post(
                            "/graphql",
                            {query: `{ seasons(film_id: ${this.$route.params.id}) { id film_id number type total_episodes total_episodes_now start_date} }`}
                        )
                    }
                ).then(resp => {
                    this.seasons = resp.data.data.seasons;
                    ElNotification({
                        title: 'Сохранение',
                        message: 'Сезон успешно сохранён',
                        type: 'success',
                    });
                    this.seasonDiaOpened = false;
                }).finally(
                    () => {
                        loading.close();
                    }
                )
            });
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

