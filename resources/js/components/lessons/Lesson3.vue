<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-3">
                        <div class="mr-auto">
                            <span class="span-header">Lesson 3</span>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn btn-dark" @click="onBack">戻る</button>
                        </div>
                    </div>
                    
                    <div class="quesion-header">電卓を作成してください。
                        <table id="app">
                            <tr>
                                <td colspan="3"><input type="text" v-model="output"></td>
                                <td><button style="width:2rem" value="C" @click="calc('c')">C</button></td>
                            </tr>
                            <tr v-for="(row, index) in items" :key=index>
                                <td v-for="(item, index) in row" :key=index>
                                    <button style="width:2rem" @click="calc(item)">{{ item }}</button>
                                </td>
                            </tr>
                        </table>
                    </div>

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
            output: '0',
            items: [
                ['7', '8', '9', '/'],
                ['4', '5', '6', '*'],
                ['1', '2', '3', '-'],
                ['0', '-', '+', '=']
            ]
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
        },
        calc(cmd) {
            if (cmd === '=') {
                this.output = eval(this.output)
            }else if (cmd === 'c') {
                this.output = '0'
            }else if (this.output === '0') {
                this.output = cmd
            }else {
                this.output += cmd
            }
        }
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
</style>