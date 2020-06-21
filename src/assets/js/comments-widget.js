// Import Vue
import Vue from 'vue';
// Import Loading
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
// Import CommentsWidget
import CommentsWidget from '../vue/CommentsWidget.vue';

Vue.use(Loading, {
    // container: null,
    canCancel: false,
    color: '#fc0000',
    backgroundColor: 'rgba(43, 51, 61, 0.5)'
});
const data_tag = document.getElementById('comments-widget');

window.comments_widget = new Vue({
    el: '#comments-widget',
    components: {
    },
    data: {
        thread: data_tag.dataset.commentsWidgetThread,
    },
    render: function (createElement) {
        return createElement(CommentsWidget, {
            props: {
                thread: this.thread
            }
        });
    }
});
