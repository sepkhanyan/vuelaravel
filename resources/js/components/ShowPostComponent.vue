<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <router-link :to="{name: 'posts'}" class="btn btn-primary">
                    <i class="fa fa-angle-double-left"></i>
                    Back
                </router-link>
                <br/>
                <div class="panel-heading">Post Detail</div>

                <div class="panel-body">
                    <br/>
                    <h2>{{post.title}}</h2>
                    <br/>
                    <p><img :src="'/images/' + post.image" id="postDetailImage"/></p>
                    <br/>
                    <p>{{post.description}}</p>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
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
                post: {}
            }
        },
        created() {
            axios.get(`/post/edit/${this.$route.params.id}`).then((response) => {
                this.post = response.data;
            });
        },
    }
</script>