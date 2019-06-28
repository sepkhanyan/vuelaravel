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
                        <input type="text" class="form-control" v-model="post.title" :class="{ 'is-invalid': errors.title }">
                        <span class="invalid-feedback" v-if="errors.title">
                                <strong>{{ errors.title[0] }}</strong>
                            </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Post Description:</label>
                        <textarea class="form-control" v-model="post.description" rows="5" :class="{ 'is-invalid': errors.description }"></textarea>
                        <span class="invalid-feedback" v-if="errors.description">
                                <strong>{{ errors.description[0] }}</strong>
                            </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Post Main Image:</label>
                    <input type="file" v-on:change="onImageChange" class="form-control" :class="{ 'is-invalid': errors.image }">
                    <span class="invalid-feedback" v-if="errors.image">
                                <strong>{{ errors.image[0] }}</strong>
                            </span>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <label>Post Images:</label>
                    <input type="file" multiple="multiple" v-on:change="onMultipleImageChange" class="form-control">
                </div>
            </div><br/>
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
                errors: {},
                post: {},
                user: {},
                attachments: [],
                formData: new FormData()
            }
        },

        methods: {

            onMultipleImageChange(e) {
                let selectedFiles=e.target.files;
                if(!selectedFiles.length){
                    return false;
                }
                for(let i=0; i < selectedFiles.length; i++){
                    this.attachments.push(selectedFiles[i]);
                }
                console.log(this.attachments);

            },

            onImageChange(e) {
                console.log(e.target.files[0]);
                this.image = e.target.files[0];
            },


            addPost() {

                const config = { headers: {'Content-Type' : 'multipart/form-data' }};

                for(let i=0; i < this.attachments.length; i++){
                    this.formData.append('pics[]',this.attachments[i]);
                }

                // let formData = new FormData();
                this.formData.append('image', this.image);

                if(this.post.title){
                    this.formData.append('title', this.post.title);
                }

                if(this.post.description){
                    this.formData.append('description', this.post.description);
                }

                this.formData.append('_method', 'POST');
                console.log(this.formData);
                axios.post('/post/create', this.formData, config)
                    .then(response => {
                        this.$router.push({path: '/'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    });
            },
            // let uri = 'http://vl.loc/api/post/create';
            // this.axios.post(uri, this.post).then((response) => {
            //     this.$router.push({name: 'posts'});
            // });
        }

    }
</script>