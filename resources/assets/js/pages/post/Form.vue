<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!--
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                -->
                <div class="box-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="title" class="col-sm-1 control-label">Title</label>
                            <div class="col-sm-10">
                                <input type="text" id="title" v-model="post.title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Tag</label>
                            <div class="col-sm-10">
                                <multiselect v-model="tags" :options="tagOptions" :multiple="true" label="name"
                                             track-by="id" placeholder="Select One"></multiselect>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-info" @click="onSubmit()">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.min.css';

    export default {
        components: {Multiselect},
        data() {
            return {
                post: {
                    title: '',
                    tag_ids: []
                },
                tags: null,
                tagOptions: [],
                oldObj: {}
            }
        },
        created() {
            this.fetchTags();
        },
        mounted() {
            this.fetchPost();
            this.oldObj = this.post
        },
        methods: {
            fetchPost() {
                if (!this.$route.meta.isAdd) {
                    this.$http.get('/api/posts/' + this.$route.params.id + '?include=tags')
                        .then((response) => {
                            this.post = response.data.data;
                            this.tags = response.data.data.tags.data;
                        })
                }
            },
            onSubmit() {
                let method = this.$route.meta.isAdd ? 'post' : 'put';
                let url = '/api/posts' + (!this.$route.meta.isAdd ? '/' + this.$route.params.id : '');
                this.post.tag_ids = this.tags.map(item => {
                    return item.id
                });

                let submitObj = this.post;
                let saveObj = this.oldObj;
                for (let key in submitObj) {
                    if (saveObj.hasOwnProperty(key)) {
                        saveObj[key] = submitObj[key];
                    }
                }
                this.$http[method](url, saveObj)
                    .then((response) => {
                        this.$router.push({name: 'admin.post'})
                    }).catch((response) => {
                    console.log(response)
                })
            },
            fetchTags() {
                this.$http.get('/api/tags')
                    .then(response => {
                        this.tagOptions = response.data.data
                    })
            }
        }
    }
</script>