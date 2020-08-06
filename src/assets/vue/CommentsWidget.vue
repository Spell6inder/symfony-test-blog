<template>
    <div>
        <ul v-if="comments" class="list-unstyled">
            <li class="media"
                v-for="(comment, commentIndex) in comments"
                :key="commentIndex"
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
        <form @submit.prevent="addComment">
            <div class="alert alert-danger" role="alert" v-if="errors.length > 0">
                <ul>
                    <li v-for="(error, errorIndex) in errors" :key="errorIndex">{{ error }}</li>
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
        name: "CommentsWidget",
        props: {
            thread: {
                type: String,
                default: ''
            },
            csrf_token: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                errors: [],
                comments: [],
                newComment: {
                    "_token": this.csrf_token
                }
            }
        },
        mounted() {
            this.loadComments();
        },
        methods: {
            async loadComments() {
              const loader = this.$loading.show()
              try {
                const comments =  await axios.get('/comment/' + this.thread)
                this.comments = comments.data
              } catch (errors) {
                this.errors = [errors.message];
              } finally {
                loader.hide()
              }
            },
            async addComment() {
              const loader = this.$loading.show()
              try {
                await axios.put('/comment/' + this.thread, this.newComment);
                this.loadComments();
                this.newComment.author = '';
                this.newComment.content = '';
                this.errors = [];
              } catch (errors) {
                this.errors = errors.response.data.errors;
              } finally {
                loader.hide()
              }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
