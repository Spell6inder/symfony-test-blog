<template>
    <div>
        <ul v-if="comments" class="list-unstyled">
            <li class="media"
                v-for="(comment, index) in comments"
            >
                <div class="media-body">
                    <h5 class="mt-0 mb-1">
                        {{ comment.author }}
                        <small class="text-muted">{{ comment.created_at }}</small>
                    </h5>
                    {{ comment.content }}
                </div>
            </li>
        </ul>
        <div v-else>
            No comments!
        </div>
        <form v-on:submit.prevent="addComment">
            <div class="alert alert-danger" role="alert" v-if="errors.length > 0">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <div class="form-group">
                <label for="newCommentAuthor">Author</label>
                <input type="text"
                       class="form-control"
                       id="newCommentAuthor"
                       v-model="newComment.author"
                       required
                >
            </div>
            <div class="form-group">
                <label for="newCommentContent">Content</label>
                <textarea class="form-control"
                          id="newCommentContent"
                          rows="3"
                          v-model="newComment.content"
                          required
                ></textarea>
            </div>
            <div class="row">
                <div class="col-4 offset-8">
                    <button type="submit" class="btn btn-primary">Leave comment</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "Order",
        components: {},
        props: {
            thread: {
                type: String,
                default: null
            }
        },
        data() {
            return {
                errors: [],
                comments: [],
                newComment: {}
            }
        },
        created() {
            this.loadComments();
        },
        methods: {
            loadComments: function () {
                axios.get('/comment/' + this.thread)
                    .then((response) => {
                        this.comments = response.data
                    })
            },
            addComment: function () {
                axios.put('/comment/' + this.thread, this.newComment)
                    .then((response) => {
                        this.loadComments();
                        this.newComment = {};
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
