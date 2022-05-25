<template>
<div id="user-removal" tabindex="-1" role="dialog" class="modal fade modal-colored-header modal-colored-header-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Delete user</h3>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <div class="i-circle text-danger"><i class="icon s7-trash"></i></div>
                    <h4>Please confirm the deletion of the following:</h4>
                </div>
                <br>
                <ul class="list-group" v-if="user">
                    <li class="list-group-item active">User with ID of <strong>#{{ user.id }}</strong> with the name <strong>{{ user.name }}</strong></li>
                </ul>
                <br>
                <div class="text-center">
                    <h4>What should be done with content owned by this user?</h4>
                </div>
                <br>
                <div class="form-group">
                    <div class="am-radio">
                        <input type="radio" name="preserve_content" id="delete-content" value="false" v-model="preserve">
                        <label for="delete-content">Delete all content</label>
                    </div>
                    <div class="am-radio radio-with-select">
                        <input type="radio" name="preserve_content" id="attribute-content" value="true" :selected="preserve" v-model="preserve">
                        <label for="attribute-content">Attribute all content to:</label> <select class="select-dropdown">
                            <option value="">Select a user...</option>
                            <option :value="user.id" v-for="(user, i) in users">{{ user.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" @click="deleteUser" class="btn btn-danger" :disabled="preserve === null">Confirm deletion</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    mounted() {
        // this.assets = this.coldAssets.length > 0 ? this.coldAssets : this.assets
        // console.log('load')
        // console.log(this.assets)
    },

    data() {
        return {
            userId: null,
            users: [],
            user: null,
            preserve: null,
            attributeUser: null
        }
    },

    watch: {
        userId: function() {
            this.getUsers();
            $('#user-removal').modal('show')
            let vm = this;
            $(".select-dropdown").select2({width: '100%', minimumResultsForSearch: Infinity}).on('select2:select', function (e) {
                vm.preserve = true;
                vm.attributeUser = e.currentTarget.value;
            });
        },
        users: function() {
            console.log(this.users)
        }
    },

    created() {
        //
    },

    methods: {
        // updated: function(assets) {
        //     this.assets = assets
		// 	let formData = new FormData();
        //     formData.append('model_id', this.assets[0].model_id);
        //     formData.append('order', true);
        //     let ids = [];
        //     Array.from(this.assets).forEach((file, i) => {
        //         ids[i] = file.id;
        //     });
        //     formData.append('data', JSON.stringify(ids));
        //     formData.append('_method', 'PATCH');
        //     axios
        //     .post(`/assets/${this.assets[0].id}`, formData)
        //     .then(res => {
        //         console.log('done')
        //         this.reloadFiles()
        //     })
        //     .catch(function (error) {
        //         console.log('error');
        //         if (error.response.status === 401 || error.response.status === 419) {
        //             window.location = '/login';
        //         // console.log(error.response.data);
        //         // console.log(error.response.status);
        //         // console.log(error.response.headers);
        //         }
        //         console.log('Could not update order');
        //     });
		// },

        // editAsset: function(asset) {
        //     // console.log('asset before entering');
        //     // console.log(asset);
        //     this.selectedAsset = asset
        //     this.modalOpen = true
        // },

        deleteUser: function() {
            if (this.attributeUser) {
                this.updateUserProjects();
            }
            let formData = new FormData();
            formData.append('attribute_user', '');
            axios
            .delete(`/users/${this.userId}`, {'attribute_user': this.attributeUser})
            .then(response => {
                window.location.reload();
            })
            .catch(function (error) {
                if (error.response && error.response.status === 401) {
                    window.location = '/login';
                }
                console.log('Could not delete user');
            });
        },

        updateUserProjects: function() {
            let formData = new FormData();
            formData.append('attribute_user', this.attributeUser);
            formData.append('name', this.user.name);
            formData.append('email', this.user.email);
            console.log(formData);
            axios
            .patch(`/users/${this.userId}`, formData)
            .then(response => {
                window.location.reload();
            })
            .catch(function (error) {
                if (error.response && error.response.status === 401) {
                    window.location = '/login';
                }
                console.log(error);
                console.log('Could not delete user');
            });
        },

        getUsers: function() {
            axios.get(`/users/${this.userId}/`)
            .then(response => {
                // console.log(response.data);
                this.users = response.data.data;
                this.user = response.data.user;
            })
            .catch(function (error) {
                if (error.response.status === 401) {
                    window.location = '/login';
                }
                console.log('Could not get directory listing');
            })
        },

        setAttributeUser: function(id) {
            console.log(id);
            this.attributeUser = id;
        }
    }
}
</script>

<style scoped>
#user-removal .select2-container {
    flex: 1;
    margin: -10px 0 0 20px;
}
#user-removal .radio-with-select {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.list-group-item.active {
    color: #fff !important;
    background-color: #ef6262 !important;
    border-color: #ef6262 !important;
}

.am-checkbox input[type="checkbox"]:checked + label:before, .am-radio input[type="checkbox"]:checked + label:before, .am-checkbox input[type="radio"]:checked + label:before, .am-radio input[type="radio"]:checked + label:before {
    color: #ef6262 !important;
    border-color: #ef6262 !important;
}
</style>
