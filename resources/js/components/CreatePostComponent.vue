<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'posts'}" class="btn btn-primary">
                <i class="fa fa-angle-double-left"></i>
                Back
            </router-link>
        </div>
        <h1>Add Post</h1>
        <form @submit.prevent="addPost" enctype="multipart/form-data" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Post Title:</label>
                        <input type="text" class="form-control" v-model="post.title">
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
            <br/>
            <!--<div class="row">-->
                <!--<div class="col-md-6">-->
                    <!--<label>Post Images:</label>-->
                    <!--<input type="file" multiple="multiple" v-on:change="onMultipleImageChange" class="form-control">-->
                <!--</div>-->
            <!--</div><br/>-->
            <div class="form-group">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                post: {},
                user: {},
                attachments: [],
                formData: new FormData()
            }
        },

        methods: {

            onImageChange(e) {
                console.log(e.target.files[0]);
                this.image = e.target.files[0];
            },


            addPost() {
                this.errors = {};

                const config = { headers: {'Content-Type' : 'multipart/form-data' }};

                // let formData = new FormData();
                this.formData.append('image', this.image);
                this.formData.append('title', this.post.title);
                this.formData.append('description', this.post.description);
                this.formData.append('_method', 'POST');
                console.log(this.formData);
                axios.post('/post/create', this.formData, config)
                    .then(response => {
                        this.$router.push({path: '/'});
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                        alert("Could not add your post");
                    });
            },
            // let uri = 'http://vl.loc/api/post/create';
            // this.axios.post(uri, this.post).then((response) => {
            //     this.$router.push({name: 'posts'});
            // });
        }

    }
</script>