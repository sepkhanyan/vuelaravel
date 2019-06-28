<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <router-link :to="{name: 'posts'}" class="btn btn-primary">
                    <i class="fa fa-angle-double-left"></i>
                    Back
                </router-link>
                <div class="panel-heading">
                    <h1>Post Details</h1>
                </div>

                <div class="panel-body">
                    <br/>
                    <h2>{{post.title}}</h2>
                    <br/>
                    <p><img :src="'/images/' + post.image" id="postDetailImage"/></p>
                    <br/>
                    <p>{{post.description}}</p>
                    <br/>
                </div>
                <br/>
                <div  v-if="images.length > 0">
                    <h2>Image Slider</h2>
                    <div>
                        <a class="prev" @click="move(-1)">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <span  v-for="image,index in images" v-if="index == active" :key="index">
                        <img :src="'/images/' + images[index].title" class="img"/>
                    </span>
                        <a class="next" @click="move(1)">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <br/>
                <h2>Comments</h2>
                <div id="comments_container" v-if="comments.length > 0" v-bind:class="{ overflow: isActive }">
                    <div v-for="comment, index in comments" class="comments-box">
                        <comments v-bind:comment="comment"></comments>
                        <a class="btn  btn-danger delete" v-if="user.is_admin == 1"
                           v-on:click="deleteComment(comment.id, index)">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </div>

                </div>
                <br/>
                <div class="comment-form">
                    <form @submit.prevent="addComment" enctype="multipart/form-data" method="post">
                    <textarea placeholder="Type here"
                              class="border" v-model="request.text" :class="{ 'is-invalid': errors.comment }">

                    </textarea>
                        <span class="invalid-feedback" v-if="errors.comment">
                                <strong>{{ errors.comment[0] }}</strong>
                            </span>
                        <div>
                            <button class="btn btn-primary">Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <br/>

    </div>
</template>

<style>
    .img {
        width: auto;
        height: 300px;
    }

    .prev, .next {
        cursor: pointer;
        width: auto;
        color: black;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }


    .overflow {
        height: 410px;
        overflow-y: scroll;
    }

    #comments_container {
        border: 2px solid #ccc;
        border-radius: 7px;
        width: 80%;

    }

    .comments-box {
        padding: 20px;
        border-bottom: 1px solid #000;
    }

    .comment-form {
        display: block;
        width: 80%;
    }

    textarea {
        width: 100%;
        border: 2px solid #ccc;
        border-radius: 7px;
        height: 150px;
        /*font-family: Verdana, Helvetica, sans-serif;*/
    }

    input {
        border: 2px solid #ccc;
        border-radius: 5px;
        padding: 5px;
    }

    .delete {
        font-size: 12px;
        cursor: pointer;
        display: inline;
        padding: 3px;
    }

    #postDetailImage {
        width: 200px;
        border-radius: 2px;
        box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);
        transition: width 1s;
    }
</style>

<script>

    export default {

        data() {
            return {
                isActive: false,
                post: {},
                user: {},
                images: [],
                comments: [],
                request: {},
                formData: new FormData(),
                errors: {},
                active: 0
            }
        },
        created() {
            axios.get(`/post/show/${this.$route.params.id}`).then((response) => {
                this.post = response.data.post;
                this.images = response.data.images;
                this.user = response.data.user;
                this.comments = response.data.comments;
                if (this.comments.length > 3) this.isActive = true;
            });

        },
        methods: {
            move(amount) {
                let newActive
                const newIndex = this.active + amount
                if (newIndex < this.images.length) this.active = newIndex
                if (newIndex == this.images.length) this.active = 0
                if (newIndex < 0) this.active = this.images.length - 1
            },
            addComment() {
                // let id = this.comments.length;
                // document.getElementById('comments_container').scrollTop = document.getElementById('comments_container').scrollHeight;

                if (this.request.text) {
                    this.formData.append('comment', this.request.text);
                }

                this.formData.append('user_id', this.user.id);
                this.formData.append('post_id', this.post.id);
                this.formData.append('_method', 'POST');
                axios.post('/add/comment', this.formData)
                    .then(response => {
                        console.log();
                        this.comments.push({
                            id: response.data.id,
                            author: response.data.author,
                            content: response.data.content
                        });
                        this.request.text = '';
                        if (this.comments.length > 3)  this.isActive = true;
                        alert("New comment posted!");
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                        // alert("Could not add your comment");
                    });
            },
            deleteComment(id, index) {
                if (confirm("Do you really want to delete this comment?")) {

                    axios.post('/comment/delete/' + id)
                        .then(response => {
                            this.comments.splice(index, 1);
                            if (this.comments.length < 3) this.isActive = false;
                        })
                        .catch(response => {
                            alert("Could not delete comment");
                        });
                }
            }
        }
    }
</script>