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
            </div>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="thumbnail">
                        <span class="pip" v-for="image,index in images">
                                 <img :src="'/images/' + image.title" class="imageThumb"/>
                                    <br/>
                                    <span v-on:click="deleteImage(image.id, index)"  class="remove" id="removeImage">
                                        <i class="fa fa-times-circle"></i>
                                    </span>
                        </span>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <button class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</template>
<style>
    .imageThumb {
        max-height: 100px;
        border: 1px solid;
        padding: 0px;
    }

    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
    }

    .remove {
        display: block;
        /*background: #444;*/
        /*border: 1px solid black;*/
        /*color: white;*/
        text-align: center;
        cursor: pointer;
    }
</style>

<script>
    export default {
        data() {
            return {
                errors : {},
                post: {},
                images: [],
                formData: new FormData(),
                attachments: []
            }
        },
        created() {
            axios.get(`/post/edit/${this.$route.params.id}`).then((response) => {
                this.post = response.data.post;
                this.images = response.data.images;
            });
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
            deleteImage(id, index) {
                if (confirm("Do you really want to delete this image?")) {

                    axios.post('/image/delete/' + id)
                        .then(response => {
                            this.images.splice(index, 1);
                        })
                        .catch(response => {
                            alert("Could not delete image");
                        });
                }
            },

            updatePost() {


                const config = { headers: {'Content-Type' : 'multipart/form-data' }};

                for(let i=0; i < this.attachments.length; i++){
                    this.formData.append('pics[]',this.attachments[i]);
                }

                if(this.image){
                    this.formData.append('image', this.image);
                }

                this.formData.append('title', this.post.title);
                this.formData.append('description', this.post.description);
                this.formData.append('_method', 'POST');
              axios.post(`/post/update/${this.$route.params.id}`, this.formData, config)
                  .then((response) => {
                  this.$router.push({path: '/'});
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