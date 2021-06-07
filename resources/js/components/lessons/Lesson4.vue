<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 4</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>

                    <div class="quesion-header">配列（inventories）をテーブルで表示しましょう</div>
                    <div class="alert alert-info" role="alert">
                        <i class="far fa-lightbulb"></i> ヒント: v-for を使用して &lt;tr&gt; を繰り返し表示する。
                    </div>
                    <table class="table table-sm" key="processes">
                        <thead>
                            <tr>
                                <th class="text-center bg-primary text-white">商品コード</th>
                                <th class="text-center bg-primary text-white">商品名</th>
                                <th class="text-center bg-primary text-white">在庫数</th>
                                <th class="text-center bg-primary text-white" style="width:7rem;">実在庫数</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="bg-white">
                                <td class="text-center align-middle">00001</td>
                                <td class="text-center align-middle">商品名0001</td>
                                <td class="text-center align-middle">100</td>
                                <td class="text-center align-middle">98</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="text-center align-middle">00002</td>
                                <td class="text-center align-middle">商品名0002</td>
                                <td class="text-center align-middle">200</td>
                                <td class="text-center align-middle">198</td>
                            </tr>
                            <tr class="bg-white">
                                <td class="text-center align-middle">00003</td>
                                <td class="text-center align-middle">商商品名0003</td>
                                <td class="text-center align-middle">300</td>
                                <td class="text-center align-middle">298</td>
                            </tr>
                        </tbody>

                        <br>
                        <center>* * * * * * * * ↓ 配列をテーブル表示 * * * * * * * *</center>
                        <thead>
                            <tr>
                                <th class="text-center bg-primary text-white">商品コード</th>
                                <th class="text-center bg-primary text-white">商品名</th>
                                <th class="text-center bg-primary text-white">在庫数</th>
                                <th class="text-center bg-primary text-white" style="width:7rem;">実在庫数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="inventory in inventories" :key="inventory.id">
                                <td class="text-center align-middle">{{ inventory.item_code }}</td>
                                <td class="text-center align-middle">{{ inventory.item_name }}</td>
                                <td class="text-center align-middle">{{ inventory.stock_num }}</td>
                                <td class="text-center align-middle">{{ inventory.real_stock_num }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        //
    },
    data () {
        return {
            inventories: [
                { id: 1, item_code: 'ITEM0001', item_name: '商品００００１', stock_num: 100, real_stock_num: 88 },
                { id: 2, item_code: 'ITEM0002', item_name: '商品００００２', stock_num: 200, real_stock_num: 155 },
                { id: 3, item_code: 'ITEM0003', item_name: '商品００００３', stock_num: 300, real_stock_num: 241 },
                { id: 4, item_code: 'ITEM0004', item_name: '商品００００４', stock_num: 400, real_stock_num: 311 },
            ],
        }
    },
    mounted () {
        //
    },
    watch: {
        //
    },
    computed: {
        //
    },
    methods: {
        onBack() {
            this.$router.push({ name: 'home' })
        }
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>