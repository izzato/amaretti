<template>
    <div>
        <span v-if="(collection === 'featured' && assets.length < 1) || (collection === 'default' || !collection)">
<!--        <button data-toggle="modal" data-target="#form-bp1" type="button" class="btn btn-space btn-primary" style="display:none">-->
<!--            <i class="icon icon-left s7-cloud"></i> Browse media-->
<!--        </button>-->
        <button type="button" class="btn btn-space btn-primary" @click="$refs.file.click()">
            <i class="icon icon-left s7-cloud-upload"></i> Upload media
        </button>
        <input class="pseudo-hidden" type="file" ref="file" multiple="multiple" @change="onFileChange">
        <span v-if="collection !== 'featured'">
            or drag and drop files.
        </span>
        </span>
        <div class="loader-overlay" v-if="loading">
        <div class="lds-ripple"><div></div><div></div></div>
        </div>
        <sortable-list v-model="assets" class="card-body asset-grid-listing" :class="type" v-on:updated="updated">
            <ul class="list-reset" slot-scope="{ items }">
                <sortable-item v-for="(asset, i) in assets" :key="asset.id" class="asset-tile">
                    <li class="bg-white px-4">
                            <div class="asset-thumb-container">
                                <div class="asset-thumb">
                                    <sortable-handle>
                                    <img :src="asset.url" alt="Thumbnail">
                                    </sortable-handle>
                                    <div class="asset-controls">
                                        <button class="btn btn-icon s7-pen" @click.prevent="editAsset(asset)"></button>

                                        <button class="btn btn-icon s7-trash btn-danger" @click.prevent="deleteAsset(asset)"></button>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="asset-meta">{{ (asset.name.split('/').slice(-1).join('').includes('.', 1)) ? (asset.name.split('/').slice(-1).join('').split('.')[0] || asset.name.split('/').slice(-1).join('')).substring(0, 10) + '.' + (asset.name.split('/').slice(-1).join('').split('.')[1] || '').substring(0, 10) : (asset.name.split('/').slice(-1).join('').split('.')[0] || asset.name.split('/').slice(-1).join('')).substring(0, 15) + '...' }}</div>
                    </li>
                </sortable-item>
            </ul>
        </sortable-list>
        <asset-editor :selectedAsset="selectedAsset" :showModal="showModal" @showModal="hideModal($event)"></asset-editor>
    </div>
</template>

<script>
import SortableList from './SortableList'
import SortableItem from './SortableItem'
import SortableHandle from './SortableHandle'

export default {
    components: {
        SortableList,
        SortableItem,
        SortableHandle,
    },

    mounted() {
        this.assets = this.coldAssets.length > 0 ? this.coldAssets : this.assets;
        this.loading = false;
        this.type = (this.collection === 'featured') ? 'asset-grid-listing-single' : '';
    },

    data() {
        return {
            assets: [],
            showModal: false,
            selectedAsset: null,
            loading: true,
            type: ''
        }
    },

    props: {
        coldAssets: {
            type: Array,
            default: function () { return [] }
        },
        slug: {
            type: String
        },
        projectId: {

        },
        collection: {
            type: String,
            default: null
        },
    },

    methods: {
        updated: function(assets) {
            this.loading = true;
            this.assets = assets;
            let formData = new FormData();
            formData.append('model_id', this.assets[0].model_id);
            formData.append('order', true);
            let ids = [];
            Array.from(this.assets).forEach((file, i) => {
                ids[i] = file.id;
            });
            formData.append('data', JSON.stringify(ids));
            formData.append('_method', 'PATCH');
            axios
            .post(`/assets/${this.assets[0].id}`, formData)
            .then(res => {
                this.loading = false;
                this.reloadFiles()
            })
            .catch(function (error) {
                if (error.response.status === 401 || error.response.status === 419) {
                    window.location = '/login';
                }
                alert('Could not update order');
            });
        },

        editAsset: function(asset) {
            this.selectedAsset = asset;
            this.showModal = true;
        },

        deleteAsset: function(asset) {
            if (!confirm('Are you sure you want to delete this item?')) {
                return;
            }
            axios
            .delete(`/assets/${asset.id}`)
            .then(response => {
                this.reloadFiles()
            })
            .catch(function (error) {
                if (error.response && error.response.status === 401) {
                    window.location = '/login';
                }
                alert('Could not delete asset');
            });
        },

        reloadFiles: function() {
            this.loading = true;
            let collection = '';
            if (this.collection) {
                collection = this.collection;
            }
            axios.get(`/projects/${this.slug}/media/${collection}`)
            .then(response => {
                this.loading = false;
                this.assets = response.data.data
            })
            .catch(function (error) {
                if (error.response.status === 401) {
                    window.location = '/login';
                }
                alert('Could not get directory listing');
            })
        },

        onFileChange (e) {
            this.loading = true;
            Array.from(e.target.files).forEach(file => {
                let formData = new FormData();
                formData.append('file', file);
                formData.append('project_id', this.projectId);
                if (this.collection) {
                    formData.append('collection', this.collection);
                }
                axios
                .post('/assets', formData)
                .then(res => {
                    this.loading = false;
                    this.reloadFiles();
                })
                .catch(function (error) {
                    if (error.response.status === 401 || error.response.status === 419) {
                        window.location = '/login';
                    }
                    alert('Could not upload file');
                });
            });
        },
        hideModal: function(event) {
            this.showModal = event;
            this.reloadFiles();
        }
    }
}
</script>

<style scoped>
    .asset-grid-listing {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 2em 0 0 0;
    }

    .asset-grid-listing-single {
        grid-template-columns: repeat(auto-fill,100%) !important;
    }

    .asset-grid-listing-single .asset-tile .asset-thumb img {
        max-height: none;
        max-width: 100%;
        height: auto;
        width: 100%;
        margin: 0 auto;
        margin-bottom: 1em;
    }

    .asset-tile .asset-controls {
        position: absolute;
        top: 45%;
        left: 50%;
        margin-left: -40px;
        display: none;
        height: 32px;
        margin-top: -16px;
        text-align: center;
    }

    .asset-tile {
        position: relative;
    }

    .asset-tile:hover .asset-controls {
        display: block;
    }

    .asset-controls button {
        font-weight: bold;
    }

    .lds-ripple {
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        left: 47%;
        top: 47%;
    }

    .loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 1001;
    }
    .lds-ripple div {
        position: absolute;
        border: 4px solid #53b9a3;
        opacity: 1;
        border-radius: 50%;
        animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
    }
    .lds-ripple div:nth-child(2) {
        animation-delay: -0.5s;
    }
    @keyframes lds-ripple {
        0% {
            top: 28px;
            left: 28px;
            width: 0;
            height: 0;
            opacity: 1;
        }
        100% {
            top: -1px;
            left: -1px;
            width: 58px;
            height: 58px;
            opacity: 0;
        }
    }
</style>
