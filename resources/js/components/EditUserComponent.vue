<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'users'}" class="btn btn-primary">
                <i class="fa fa-angle-double-left"></i>
                Back
            </router-link>
        </div>
        <h1>Edit User</h1>
        <form v-on:submit.prevent="updateUser()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Name:</label>
                        <input type="text" class="form-control" v-model="user.name" :class="{ 'is-invalid': errors.name}">
                        <span class="invalid-feedback" v-if="errors.name">
                                <strong>{{ errors.name[0] }}</strong>
                            </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Email:</label>
                        <input type="email" class="form-control" v-model="user.email" :class="{ 'is-invalid': errors.email}" >
                        <span class="invalid-feedback" v-if="errors.email">
                                <strong>{{ errors.email[0] }}</strong>
                            </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Password:</label>
                        <input type="password"  class="form-control" v-model="user.password" :class="{ 'is-invalid': errors.password }">
                    </div>
                </div>
            </div>
            <!--<br/>-->
            <!--<div class="row">-->
            <!--<div class="col-md-6">-->
            <!--<label>Post Images:</label>-->
            <!--<input type="file" multiple="multiple" v-on:change="onMultipleImageChange" class="form-control">-->
            <!--</div>-->
            <!--</div>-->
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: {},
                user: {}
            }
        },
        created() {
            axios.get(`/user/edit/${this.$route.params.id}`).then((response) => {
                this.user = response.data.user;
            });
        },
        methods: {
            updateUser() {
                this.errors = {};

                axios.post(`/user/update/${this.$route.params.id}`, this.user)
                    .then((response) => {
                        this.$router.push({path: '/users'});
                })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    });
            }
        }
        //
        // methods: {
        //     saveForm() {
        //
        //         var post = this.post;
        //         axios.post('/post/edit/' + this.postId, post)
        //             .then(function (resp) {
        //                 this.$router.replace('/');
        //             })
        //             .catch(function (resp) {
        //                 console.log(resp);
        //                 alert("Could not create your post");
        //             });
        //     }
        // }
    }
</script>