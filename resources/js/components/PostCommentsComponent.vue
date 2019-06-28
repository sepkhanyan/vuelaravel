<template>

    <div>
                <p class="author">
                    {{comment.author}}
                </p>
                <p  class="content-comment">
                    {{comment.content}}
                </p>

    </div>


</template>

<style>

    .author{
        /*margin: 10px 0;*/
        font-weight: bold;
    }
</style>

<script>
    export default {
        data() {
            return {

            }
        },
        props : ['comment'],
    }
</script>