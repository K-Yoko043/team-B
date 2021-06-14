<template>
<div class="container">
	<div class="card text-center">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-center mb-2">
                <back-button />
                <div class="form-group">
                    <form action="profile" method="POST" enctype="multipart/form-data">
                        <label class="font-weight-bold" for="photo">プロフィール写真</label>
                        <!-- <button type="file" class="form-control btn btn-outline-secondary">ファイルを選択</button> -->
                        <!-- <input type="file" class="form-control" name="file"> -->
                        <input type="file" class="form-control-file" accept="image/*" @change="onImageChange" :name="name">
                        <img class="form-control-file" v-if="uploadedImage" :src="uploadedImage">
                        <img class="form-control-file" v-else src="/image/noImage.jpg">
                    </form>
                    <div class="form-group">
                        <label class="font-weight-bold">ユーザ名</label>
                        <input v-model="own.name" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control-btn btn btn-primary">保存</button>
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
        name: {
            type: String,
            default: "",
        },
        src: {
            type: String,
            default: "",
        },
    },
    data: function () {
        return {
            uploadedImage: this.src,
        };
    },
    mounted () {
        //
    },
    watch: {
        //
    }, 
    computed: {
        own() {
            return this.$store.state.user
        },
    },
    methods: {
        onImageChange(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.createImage(files[0]);
            } else {
                this.uploadedImage = this.src;
            }
        },
        createImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.uploadedImage = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/variables";
.btn-menu {
    height: 6rem;
    width: 9rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
