<template>
    <div :id="`asset-editor-` + _uid" tabindex="-1" role="dialog" class="modal modal-colored-header" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content" style="position: relative">
                <div class="modal-header">
                    <button type="button" aria-hidden="true" class="close md-close" @click="$emit('showModal', false)"><i class="icon s7-close"></i></button>
                    <a :href="downloadUrl" class="close md-close" style="font-weight: 600" target="_blank"><i class="icon s7-cloud-download"></i></a>
                    <h3 class="modal-title text-left">Asset</h3>
                </div>
                <div class="modal-body" style="height: calc(100vh - 200px);position: relative;overflow: scroll;">
                    <div class="editor-main">

                        <div class="editor-preview">

                            <div class="editor-preview-image">
                                <div class="image-wrapper">
                                    <img :src="asset.url" :alt="asset.name" class="asset-thumb">
                                </div>
                            </div>

                            <!-- <div class="editor-file-actions">
                                <button type="button" class="btn btn-default">Focal point
                                </button>

                                <button type="button" class="btn btn-default">Rename File
                                </button>

                                <button type="button" class="btn btn-default">Move file
                                </button>
                            </div> -->
                        </div>
                        <div class="editor-form text-left">
                            <label>File name:</label> <span :title="asset.url">{{ asset.file_name }}</span><br>
                            <label>File type:</label> {{ asset.mime_type }}<br>
                            <label>Uploaded on:</label> {{ asset.created_at }}<br>
                            <span v-show="asset.created_at !== asset.updated_at"> <label>Last modified on:</label> {{ asset.updated_at }}<br></span>
                            <label v-show="asset.size">File size:</label> {{ Math.ceil(asset.size/1000) }} MB<br>
                            <label v-show="dimensions">Dimensions:</label> {{ dimensions }}<br>
                            <div class="editor-form-fields">
                                <div class="publish-fields">
                                    <div class="form-group text-fieldtype width-100 ">
                                        <div class="field-inner">

                                            <label class="block" for="asset-alt">
                                                Alt Text
                                            </label>

                                            <small class="help-block"><p>An alternate name for accessibility.</p></small>

                                            <input tabindex="0" class="form-control type-text" v-model="asset.name" type="text" id="asset-alt" name="name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click.prevent="deleteAsset(asset)">Delete</button>
                    <button type="button" class="btn btn-primary" @click.prevent="updateAsset(asset)">Save</button>
                </div>
            </div>
        </div>
        <div class="loader-overlay" v-if="loading">
            <div class="lds-ripple"><div></div><div></div></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                asset: {},
                dimensions: null,
                loading: true,
            }
        },

        props: {
            showModal: Boolean,
            selectedAsset: Object,
        },

        watch: {
            showModal: function() {
                this.asset = this.selectedAsset;

                this.getMeta(
                    this.asset.url,
                    (width, height) => { this.dimensions = `${width} × ${height}` }
                );

                if (this.showModal) {
                    $('#asset-editor-' + this._uid).modal()
                } else {
                    $('#asset-editor-' + this._uid).modal('hide')
                }
                this.loading = false;
            }
        },

        computed: {
            downloadUrl: function() {
                return `/assets/${ this.asset.id }/download`
            }
        },

        methods: {
            updateAsset: function(asset) {
                this.loading = true;
                let formData = new FormData();
                formData.append('id', asset.id);
                formData.append('name', asset.name);
                formData.append('_method', 'PATCH');
                axios
                .post(`/assets/${asset.id}`, formData)
                .then(res => {})
                .catch(function (error) {
                    if (error.response.status === 401 || error.response.status === 419) {
                        window.location = '/login';
                    }
                    alert('Could not update asset');
                });
                this.loading = false;
            },
            deleteAsset: function(asset) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    return;
                }
                this.loading = true;
                axios
                .delete(`/assets/${asset.id}`)
                .then(response => {
                    this.$emit('showModal', false)
                })
                .catch(function (error) {
                    if (error.response && error.response.status === 401) {
                        window.location = '/login';
                    }
                    console.log('Could not delete asset');
                });
                this.loading = false;
            },
            getMeta: function(url, callback) {
                const img = new Image();
                img.src = url;
                img.onload = function() { callback(this.width, this.height); }
            }
        }
    }
</script>

<style scoped>
.modal-body {
    padding: 0;
}
.editor-main {
    flex-grow: 1;
    display: flex;
    justify-content: space-between;
    height: 100%;
}
.editor-preview {
    background: #394045;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;

    flex-basis: 64%;
}
.editor-preview-image {
    flex-grow: 1;
    /* padding: 30px 30px 90px; */
    padding: 30px 30px 30px;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.image-wrapper {
    flex: 1 1 auto;
    position: relative;
}
.image-wrapper img {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
    box-shadow: 0 0 30px rgba(0,0,0,.35);

    vertical-align: middle;
    border: 0;
}

.editor-form {
    flex-basis: 36%;
    padding: 30px;
    overflow: scroll;
}

.editor-form label {
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
