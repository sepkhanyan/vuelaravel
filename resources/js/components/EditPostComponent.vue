<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'posts'}" class="btn btn-primary">
                <i class="fa fa-angle-double-left"></i>
                Back
            </router-link>
        </div>
        <h1>Edit Post</h1>
        <form v-on:submit.prevent="updatePost()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Post Title:</label>
                        <input type="text" class="form-control" v-model="post.title" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Post Description:</label>
                        <textarea class="form-control" v-model="post.description" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Post Main Image:</label>
                    <input type="file" v-on:change="onImageChange" class="form-control">
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
                <div class="col-xs-12 form-group">
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
                post: {},
                formData: new FormData()
            }
        },
        created() {
            axios.get(`/post/edit/${this.$route.params.id}`).then((response) => {
                this.post = response.data;
            });
        },
        methods: {
            onImageChange(e) {
                console.log(e.target.files[0]);
                this.image = e.target.files[0];
            },

            updatePost() {
                this.errors = {};

                const config = { headers: {'Content-Type' : 'multipart/form-data' }};
                this.formData.append('image', this.image);
                this.formData.append('title', this.post.title);
                this.formData.append('description', this.post.description);
                this.formData.append('_method', 'POST');
              axios.post(`/post/update/${this.$route.params.id}`, this.formData, config).then((response) => {
                    this.$router.push({name: 'posts'});
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