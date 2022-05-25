<template>
    <div class="card media-browser">
        <div class="card-header">
            <h4><!--Media--></h4>

            <div class="asset-browser-actions">
                <!-- <form>
                    <input type="text" class="form-control" v-model="searchQuery" placeholder="Search..." style="max-height: 38px; overflow: hidden">
                </form> -->

                <div class="btn-group btn-space" v-if="Object.keys(selected).length > 0">
                    <button type="button" class="btn btn-danger" @click="deleteAsset()">Delete</button>
                    <!-- <button type="button" class="btn btn-default">Copy</button>
                    <button type="button" class="btn btn-default">Move</button> -->
                </div>

                <div class="btn-group btn-space">
                    <button class="btn btn-default" @click="selectAll" v-show="files.length > 0">Check all</button>
                    <button class="btn btn-default" @click="clearSelection" v-show="Object.keys(selected).length > 0">Uncheck all</button>
                </div>

                <!-- <div class="btn-group btn-space">
                    <button class="btn btn-default btn-icon active"><img src="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/svg/grid-three-up.svg" alt="Grid view"></button>
                    <button class="btn btn-default btn-icon"><img src="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/svg/list.svg" alt="List view"></button>
                </div> -->

                <div class="btn-group btn-space">
                    <!-- <button class="btn btn-default" @click="makeDirectory">New Folder</button> -->
                    <button type="button" class="btn btn-default" @click="$refs.file.click();">
                        Upload
                    </button>
                    <input class="pseudo-hidden" type="file" ref="file" multiple="multiple" @change="onFileChange">
                </div>
            </div>
        </div>
        <div class="card-body asset-grid-listing">
            <div class="selection"></div>
            <!-- <div class="asset-tile is-image is-selected-x">
                <div class="asset-thumb-container">
                    <div class="asset-thumb" v-if="directory === '/'">
                        <svg @click="upDirectory()" width="100" height="100" class="octicon octicon-arrow-left" viewBox="0 0 10 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M6 3L0 8l6 5v-3h4V6H6V3z" fill="#ccc"></path></svg>
                    </div>
                    <div class="asset-thumb" v-else>
                        <svg @click="upDirectory()" width="100" height="100" class="octicon octicon-arrow-left" viewBox="0 0 10 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M6 3L0 8l6 5v-3h4V6H6V3z" fill="#6bc3b0"></path></svg>
                    </div>
                </div>

                <div class="asset-meta">Back</div>
            </div> -->
            <div :class="{ selected: !!selected[file[0]], selecting: !!selecting[file[0]] }" class="asset-tile is-image selectable" :title="file[0]" v-for="(file, i) in files">
                <div class="asset-thumb-container" @click="selectItem(file)">
                    <div class="asset-thumb">
                        <img :src="file[0]" v-if="file[1].includes('image')">
                        <svg v-if="!file[1].includes('image') && file[1] !== 'directory'" width="100" height="100" class="octicon octicon-file" viewBox="0 0 12 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M6 5H2V4h4v1zM2 8h7V7H2v1zm0 2h7V9H2v1zm0 2h7v-1H2v1zm10-7.5V14c0 .55-.45 1-1 1H1c-.55 0-1-.45-1-1V2c0-.55.45-1 1-1h7.5L12 4.5zM11 5L8 2H1v12h10V5z" fill="#6bc3b0"></path></svg>
                        <svg v-if="file[1] == 'directory'" @click="changeDirectory(file)" height="100" class="octicon octicon-file-directory align-middle" viewBox="0 0 14 16" version="1.1" width="100" aria-hidden="true"><path fill-rule="evenodd" d="M13 4H7V3c0-.66-.31-1-1-1H1c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1V5c0-.55-.45-1-1-1zM6 4H1V3h5v1z" fill="#6bc3b0"></path></svg>
                    </div>
                </div>

                <div class="asset-meta">{{ (file[0].split('/').slice(-1).join('').includes('.', 1)) ? (file[0].split('/').slice(-1).join('').split('.')[0] || file[0].split('/').slice(-1).join('')).substring(0, 10) + '.' + (file[0].split('/').slice(-1).join('').split('.')[1] || '').substring(0, 10) : (file[0].split('/').slice(-1).join('').split('.')[0] || file[0].split('/').slice(-1).join('')).substring(0, 10) }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    function b64EncodeUnicode(str) {
        // first we use encodeURIComponent to get percent-encoded UTF-8,
        // then we convert the percent encodings into raw bytes which
        // can be fed into btoa.
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
        }));
    }
    export default {
        mounted() {
            let vm = this;
            this.selectedAssets.map(function(asset, index) {
                vm.selected[asset.url] ? vm.$delete(vm.selected, asset.url) : vm.$set(vm.selected, asset.url, asset)
            });
        },

        data() {
            return {
                files : [],
                selected: {},
                selecting: []
            }
        },

        props: {
            selectedAssets: {
                type: Array,
                default: function () { return [] }
            }
        },

        created() {
            this.reloadFiles()
        },

        methods: {
            reloadFiles () {
                axios.get('/assets')
                .then(response => {
                    // console.log(response.data);
                    this.files = response.data.data
                })
                .catch(function (error) {
                    if (error.response.status === 401 || error.response.status === 419) {
                        window.location = '/login';
                    }
                    console.log('Could not get directory listing');
                })
            },
            deleteAsset () {
                let vm = this;
                Object.keys(this.selected).forEach(function(asset, index) {
                    axios
                    .delete('/assets/' + b64EncodeUnicode(asset))
                    .then(res => {
                        vm.clearSelection()
                        vm.reloadFiles()
                    })
                    .catch(function (error) {
                        if (error.response.status === 401 || error.response.status === 419) {
                            window.location = '/login';
                        }
                        console.log('Could not delete asset');
                    });
                });
            },
            onFileChange (e) {
                Array.from(e.target.files).forEach(file => {
                    let formData = new FormData();
                    formData.append('file', file);
                    axios
                    .post('/assets', formData)
                    .then(res => {
                        this.reloadFiles();
                    })
                    .catch(function (error) {
                        if (error.response.status === 401 || error.response.status === 419) {
                            window.location = '/login';
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                        }
                        console.log('Could not upload file');
                    });
                });
            },
            clearSelection() {
                this.selected = {};
            },

            selectItem(item) {
                this.selected[item[0]] ? this.$delete(this.selected, item[0]) : this.$set(this.selected, item[0], item)
            },

            selectAll() {
                let vm = this;
                this.files.map(function(asset, index) {
                    vm.$set(vm.selected, asset[0], asset)
                });
            },

            // hashCode(str) {
            //     return str.split('').reduce((prevHash, currVal) =>
            //         (((prevHash << 5) - prevHash) + currVal.charCodeAt(0))|0, 0);
            // }
        }
    }
</script>
