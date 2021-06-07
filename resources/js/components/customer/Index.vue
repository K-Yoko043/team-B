<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">顧客一覧</span>
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
                                <th class="text-center">商品コード</th>
                                <th class="text-center">商品名</th>
                                <th class="text-center">郵便番号</th>
                                <th class="text-center">アドレス</th>
                                <th class="text-center">電話</th>
                                <th class="text-center">Fax</th>
                                <th class="text-center">休業日</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr v-for="item in items" class="clickable" :key="item.id" @click="onShow(item.id)">
                                <td class="text-center align-middle">{{item.code}}</td>
                                <td class="text-center align-middle">{{item.name}}</td>
                                <td class="text-center align-middle">{{item.postal_code}}</td>
                                <td class="text-center align-middle">{{item.address}}</td>
                                <td class="text-center align-middle">{{item.tel}}</td>
                                <td class="text-center align-middle">{{item.fax}}</td>
                                <td class="text-center align-middle">{{item.closing_day}}</td>
                            </tr>
                        </tbody>
                        <loading :active.sync="isLoading"></loading>
                    </table>
                    <pagination
                        :page="currentPage"
                        :itemsPerPage="itemsPerPage"
                        :maxVisiblePages="maxVisiblePages"
                        :totalItems="totalItems"
                        @pageChange="pageChange"
                    />
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
            items: [],
            isLoading: false,

            currentPage: 0,
            itemsPerPage: 20,
            maxVisiblePages: 4,
            totalItems: 0,
            offset: 0,
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
                api.get('/api/customer'),
            ]).then(axios.spread((res1, res2, res3, res4) => {
                this.items = res1.data
                
                this.isLoading = false
            }))
        },
        onCreate: function () {
            this.$router.push({ name: 'customer.create' })
        },
        onShow: function (customer_id) {
            this.$router.push({ name: 'customer.show', params: {customer_id: customer_id} })
        },
        onBack: function () {
            this.$router.go(-1)
        },
        pageChange: function (page, start, end) {
            // console.log(page, start, end);
            if (end == 1) return
            this.currentPage = page
            this.offset = start
            this.getItems()
        },
    },
    components: {
        // Loading
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>