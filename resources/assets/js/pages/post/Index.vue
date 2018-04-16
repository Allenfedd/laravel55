<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Post List</h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm">
                            <!--<input type="text" name="table_search" class="form-control pull-right"  style="width:30%;" placeholder="Search">-->
                            <div class="input-group-btn">
                                <!--<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>-->
                                <router-link :to="{ name:'admin.post.create' }" class="btn btn-primary btn-md pull-right">Create Post</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Tag</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                        <tr v-for="item in items">
                            <td>{{item.id}}</td>
                            <td>{{item.title}}</td>
                            <td class="col-md-1">
                                <div v-if="item.tags === undefined">
                                    <b class="badge bg-aqua"></b>
                                </div>
                                <div v-else>
                                    <b v-for="tag in item.tags.data" class="badge bg-aqua">{{tag.name}}</b>
                                </div>
                            </td>
                            <td>{{item.created_at}}</td>
                            <td class="col-sm-3">
                                <!--<div class="btn-group">-->
                                <router-link :to="{ path: '/admin/post/' + item.id +'/edit'}"
                                             class="btn btn-warning btn-xs" style="margin-right: 10px;">Edit
                                </router-link>
                                <button class="btn btn-danger btn-xs" @click="deletePost(item)" title="Delete"><i
                                        class="fa fa-trash"></i>
                                </button>
                                <!--</div>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <!--<span>Showing {{}} of {{ pagination.total }}</span>-->
                    <ul class="pagination no-margin pull-right">
                        <li :class="{ disabled: isFirstPage }">
                            <a href="#" @click.prevent="goPreviousPage()">Previous</a>
                        </li>
                        <li v-for="page in pages" :class="{ active: currentPage ==page }">
                            <a href="#" @click.prevent="goPage(page)">{{ page }}</a>
                        </li>
                        <li :class="{ disabled: isLastPage }">
                            <a href="#" @click.prevent="goNextPage()">Next</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: {},
                currentPage: 1,
                pagination: {}
            }
        },
        mounted() {
            this.getPosts();
        },
        computed: {
            pages() {
                return this.pagination.total_pages
            },
            isFirstPage() {
                return this.currentPage == 1
            },
            isLastPage() {
                return this.currentPage == this.pagination.total_pages
            }
        },
        methods: {
            goPage(page) {
                if (page !== this.currentPage) {
                    this.currentPage = page
                    this.getPosts();
                }
            },
            goPreviousPage() {
                if (!this.isFirstPage) {
                    this.currentPage--;
                    this.getPosts();
                }
            },
            goNextPage() {
                if (!this.isLastPage) {
                    this.currentPage++;
                    this.getPosts();
                }
            },
            getPosts() {
                this.$http.get('/api/posts?include=tags&page=' + this.currentPage)
                    .then((response) => {
                        this.items = response.data.data
                        this.pagination = response.data.meta.pagination
                        this.currentPage = response.data.meta.pagination.current_page
                    })
            },
            deletePost(post) {
                swal({
                    title: 'Are you sure?',
                    text: `Post ${post.title} will be removed.`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.value) {
                        this.$http.delete('/api/posts/' + post.id)
                            .then(() => {
                                this.getPosts();
                                toastr.success('Post removed.');
                            }).catch(() => {
                            swal('Ooops...', 'Something went wrong!', 'error');
                        })
                    }
                });
            }
        }
    }
</script>