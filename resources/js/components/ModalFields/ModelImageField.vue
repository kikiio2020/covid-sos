<template>
    <div v-if="modelExists">
        <b-container v-if="'' !== imageSrc" class="align-right">
            <b-row>
                <b-col cols="11">
                    <a :href="imageSrc" target='_blank' class="mr-2">
                        <b-img :src="imageSrc" height="50"></b-img>
                    </a>
                    <b-button variant="danger" @click="confirmRemoveImage" :disabled="removing">
                        <b-icon v-if="removing" icon="arrow-clockwise" animation="spin"></b-icon>
                        <b-icon icon="x-square" v-else></b-icon>
                    </b-button>
                </b-col>
                <b-col cols="1" class="p-1"></b-col>
            </b-row>
        </b-container>
        <b-container v-else>
            <b-row>
                <b-col cols="11" class="">
                    <ValidationProvider :name="caption" :rules="rules" v-slot="validationContext">
                        <b-form-file
                            v-model="fileObject"
                            :name="name"
                            :id="id"
                            :placeholder="placeHolder"
                            class="my-1 mr-3"
                            :state="getValidationState(validationContext)"
                            aria-describedby="feedback"
                            @change="validationContext.validate"
                            :disabled="disabled || uploading"
                        ></b-form-file>
                        <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </ValidationProvider>
                </b-col>
                <b-col cols="1" class="p-1">
                    <b-button variant="success" class="float-right" @click="upload" :disabled="!valid || uploading" >
                        <b-icon v-if="uploading" icon="arrow-clockwise" animation="spin"></b-icon>
                        <b-icon v-else icon="upload" :animation="valid?'cylon-vertical':''"></b-icon>
                    </b-button>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>
<script>
import { BSpinner, BIcon, BButton, BFormFile } from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, image, size } from 'vee-validate/dist/rules';

Vue.component('b-icon', BIcon);
Vue.component('b-button', BButton);

extend('image', image);
extend('size', {
    ...size,
    message: "{_field_} is too big"
});
extend('required', {
    ...required,
    message: "{_field_} is required"
});

export default {
    components: {ValidationProvider, ValidationObserver},
    props: {
        id: String,
        name: String, 
        caption: String, 
        placeHolder: String, 
        disabled: {
            default: false
        }, 
        maxSize: {
            default: 800,
            type: String
        }, 
        api: String,
        modelId: Number
    },
    data() {
        return {
            fileObject: null,
            modelExists: false,
            valid: false,
            imageSrc: '',
            uploading: false,
            removing: false,
        }
    },
    methods: {
        upload(){
            this.uploading = true;
            let formData = new FormData();
            formData.append(this.id, this.fileObject);
            axios.post(this.modelImageApi, formData, {headers: { 'content-type': 'multipart/form-data' }})
                .then((response) => {
                    this.uploading = false;
                    this.imageSrc = this.getModelImageApi();
                    this.$root.$bvToast.toast('Image Successfully Uploaded', {
                        title: 'Image Upload',
                        variant: 'success',
                    });
                }).catch((error) => {
                    console.log(error);
                    this.uploading=false;
                    this.imageSrc = '';
                    this.$root.$bvToast.toast('Image Upload Failed', {
                        title: 'Image Upload',
                        variant: 'danger',
                    });
                });    
        },
        confirmRemoveImage() {
            this.$bvModal.msgBoxConfirm('Remove Image?')
                .then(value => {
                    this.remove();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        remove() {
            this.removing = true;
            axios.delete(this.modelImageApi)
                .then(value => {
                    this.removing =false;
                    this.imageSrc = '';
                })
                .catch(err => {
                    console.log(err);
                    this.removing = false;
                });
        },
        getValidationState({ dirty, validated, valid = null, invalid }) {
            this.valid = validated && valid;
            
            return dirty || validated ? valid : null;
        },
        getModelImageApi() {
            return this.modelImageApi + '?' + this.timeStamp();
        },
        timeStamp() {
            return Math.floor(Date.now() / 1000);
        }
    },
    computed: {
        rules: function () {
            return 'image|size:' + this.maxSize;
        },
        modelApi: function () {
            return this.api + '/' + this.modelId;
        },
        modelImageApi: function () {
            return this.api + '/' + this.modelId + '/images/' + this.id;
        },
    },
    mounted() {
        axios.get(this.modelApi).then(response => {
            this.modelExists = true;
            if (response.data.data[this.id]) {
                this.imageSrc = this.getModelImageApi();
            }
        }).catch(function(){
        });
    }
}
</script>