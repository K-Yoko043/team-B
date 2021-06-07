<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Goriller一覧</span>
                        </div>
                        <div class="align-self-center mr-3">
                            <button type="button" class="btn btn-primary" @click="onCreate"><i class="fas fa-plus"></i> 新規追加</button>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <table class="table-custom" key="processes">
                        <thead>
                            <tr>
                                <th class="text-center">氏名</th>
                                <th class="text-center">ユーザ名</th>
                                <th class="text-center">権限</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr v-for="goriller in gorillers" class="clickable" :key="goriller.id" @click="onShow(goriller.id)">
                                <td class="text-center align-middle">{{goriller.full_name}}</td>
                                <td class="text-center align-middle">{{goriller.user_name}}</td>
                                <td class="text-center align-middle">
                                    <div v-if="goriller.is_admin">管理者</div>
                                </td>
                            </tr>
                        </tbody>
                        <loading :active.sync="isLoading"></loading>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: [
        
    ],
    data () {
        return {
            gorillers: [],
            isLoading: false,
        }
    },
    mounted () {
        this.inspected_on = new moment().format('YYYY-MM-DD')
        this.getItems()
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            axios.all([
                api.get('/api/goriller'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.gorillers = res1.data
                
                this.isLoading = false
            }))
        },
        onCreate: function () {
            this.$router.push({ name: 'goriller.create' })
        },
        onShow: function (goriller_id) {
            this.$router.push({ name: 'goriller.show', params: {goriller_id: goriller_id} })
        },
        onBack: function () {
            this.$router.go(-1)
        }
    },
    components: {
        // Loading
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>