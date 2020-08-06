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

new Vue({
    render: h => h(
        CommentsWidget,
        {
            props: {
                thread: data_tag.dataset.commentsWidgetThread,
                csrf_token: data_tag.dataset.commentsWidgetCsrf_token,
                csrf_field: data_tag.dataset.commentsWidgetCsrf_field ? data_tag.dataset.commentsWidgetCsrf_field : "_field"
            }
        }
    )
}).$mount('#comments-widget');
