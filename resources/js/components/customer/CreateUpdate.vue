<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" key="admin">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">{{title}}</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    <form>
                        <div class="form-group required-label row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">顧客番号</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="code" v-model="customer.code"/>
                            </div>
                        </div>
                        <div class="form-group required-label row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">顧客名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="name" v-model="customer.name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">郵便番号</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="postal_code" v-model="customer.postal_code"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phonetic_name" class="col-sm-4 col-form-label text-md-right">アドレス</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="address" v-model="customer.address"/>
                            </div>
                        </div>
                        <div class="form-group required-label row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">電話番号</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="tel" v-model="customer.tel"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">Fax</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="fax" v-model="customer.fax"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">休業日</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="closing_day" v-model="customer.closing_day"/>
                            </div>
                        </div>
                        
                        <div class="row-line">
                            <transition name="fade" mode="out-in">
                            <div class="alert alert-danger" role="alert" v-if="invalid">
                            {{errorMessage}}
                            </div>
                            </transition>
                        </div>
                    </form>
                    <div class="d-flex justify-content-start mt-4">
                        <div class="mr-auto">
                            <button type="button" class="btn btn-outline-danger" v-if="enable_delete" @click="onDelete">この商品を削除する</button>
                        </div>
                        <div class="mr-3">
                            <button type="button" class="btn btn-dark" @click="onBack">キャンセル</button>
                        </div>
                        <div v-if="mode!='create'">
                            <button type="button" class="btn btn-primary" @click="onStore">保存する</button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-primary" @click="onStore">登録する</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: [
        'customer_id',
    ],
    data () {
        return {
            customer: {
                id: '',
                code: '',
                name: '',
                postal_code: '',
                address: '',
                tel: '',
                fax: '',
                closing_day: '',
                deleted_at: '',
            },        
            invalid: false,
            errorMessage: '',

            isLoading: false,
            fullPage: false,
        }
    },
    created () {
        this.getItems()
    },
    watch: {
        // 
    },
    computed: {
        own: function () {
            return this.$store.state.user
        },
        title: function () {
            return this.mode == 'create' ? '顧客の新規作成' : '顧客の編集'
        },
        mode: function () {
            return this.customer_id ? 'update' : 'create'
        },
        enable_delete: function () {
            if (this.mode == 'create') {
                return false
            }
            return this.own.customer_id != this.customer_id
        },
    },
    methods: {
        getItems: function () {
            this.isLoading = true;
            const api = axios.create()
            if (this.mode == "create") {
                // axios.all([
                //     api.get('/api/factory/selector'),
                //     api.get('/api/department/selector'),
                // ]).then(axios.spread((res1, res2, res3) => {
                //     this.factories = res1.data
                //     this.departments = res2.data
                // }))
                this.isLoading = false
            } else {
                axios.all([
                    api.get('/api/customer/'+this.customer_id),
                    // api.get('/api/factory/selector'),
                    // api.get('/api/department/selector'),
                ]).then(axios.spread((res1, res2, res3) => {
                    this.customer = res1.data
                    // this.factories = res2.data
                    // this.departments = res3.data
                    this.isLoading = false
                }))
            }
        },
        onStore: function () {
            this.invalid = false

            if (!this.customer.code) {
                this.errorMessage = '顧客番号を入力してください。'
                this.invalid = true
                return
            }
            if (!this.customer.name) {
                this.errorMessage = '顧客名を入力してください。'
                this.invalid = true
                return
            }
            if (!this.customer.tel) {
                this.errorMessage = '電話番号を入力してください。'
                this.invalid = true
                return
            }

            let _this = this
            if (this.mode == 'create') {
                axios.post('/api/customer', {
                    customer: this.customer,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('登録しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            } else {
                axios.put('/api/customer/'+this.customer.id, {
                    customer: this.customer,
                })
                .then(function (resp) {
                    if (resp.data.result) {
                        alert('更新しました。')
                        _this.$router.go(-1)
                    } else {
                        _this.errorMessage = resp.data.errorMessage
                        _this.invalid = true
                    }
                })
                .catch(function (resp) {
                    console.log(resp)
                });
            }
        },
        onBack: function () {
            this.$router.go(-1)
        },
        onDelete: function () {
            if (!confirm('削除してもよろしいですか？')) {
                return
            }
            let _this = this
            axios.delete('/api/customer/'+this.customer.id)
            .then(function (resp) {
                alert('削除しました。')
                _this.$router.go(-1)
            })
            .catch(function (resp) {
                console.log(resp)
            })
            .finally(function () {
                //
            })
        },

    },
    components: {
        // Loading
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.row-line {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    padding-bottom: 1rem;
}
.form-content {
    width: 12rem;
    padding-left: 1rem;
    padding-right: 1rem;
    display: inline-block;
}
.form-content-lg {
    width: 15rem;
    padding-left: 1rem;
    padding-right: 1rem;
    display: inline-block;
}
.required-label label:after {
    display: inline-block;
    margin-left: 5px;
    padding: 2px 4px;
    border-radius: 3px;
    background-color: #ec5d53;
    color: #fff;
    content: "必須";
    font-size: 9px;
    line-height: 10px;
}
</style>
