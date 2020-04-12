<template>
<div class="my-3 mr-3">
    <b-form-select
        v-model="selected"
        @input="$emit('select', vModel)"
        :name="name"
        :id="id"
        :required="required"
        :disabled="disabled"
        :state="state"
        aria-describedby="ariaDescribedby"
        :options="options"
    ></b-form-select>

    <ValidationProvider v-if="isShowOther()" :name="optionCaptions[selected]" rules="required" v-slot="validationContext">
        <b-form-input
            :name="optionName"
            :id="optionId"
            v-model="otherText"
            @input="$emit('input', otherText)"
            :required="required"
            :placeholder="optionPlaceHolders[selected] + ' *'"
            :state="getValidationState(validationContext)"
            aria-describedby="feedback"
            :disabled="disabled"
        ></b-form-input>
        <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>    
</div>
</template>
<script>
import { BFormSelect, BFormInput } from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, image, size } from 'vee-validate/dist/rules';

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
        options: Array,
        optionPlaceHolders: {},
        optionCaptions: {},
        optionName: String,
        optionId: String,
        disabled: {
            default: false
        }, 
        value: {
            selected: null,
            otherText: '',
        },
        required: Boolean,
        state: {},
        ariaDescribedby: '',
    },
    data() {
        return {
            selectOptions: this.options,
            selected: this.value.selected,
            otherText: this.value.otherText,
        }
    },
    methods: {
        isShowOther() {
            if (this.optionPlaceHolders[this.selected]) {
                return true;    
            }
            return false;
        },
        getValidationState({ dirty, validated, valid = null, invalid }) {
            return dirty || validated ? valid : null;
        },
    },
    computed: {},
    mounted() {
    }
}
</script>