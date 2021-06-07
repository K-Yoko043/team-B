<template>
<div class="d-flex flex-wrap">
    <div class="card card-tab" v-for="goriller in gorillers" :key="goriller.id">
        <div class="card-body">
            <button type="button" class="btn btn-lg btn-link btn-select" @click="onClick(goriller)">{{goriller.full_name}}</button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: [
        'gorillers',
    ],
    methods: {
        onClick: function (item) {
            this.$emit('selected', item)
        }
    }
}
</script>

<style scoped>
.card-tab {
    box-shadow: none;
    border: none;
}
.card-body {
    padding: .9rem;
}
.btn-select {
    font-size: 1.5rem;
}
</style>
