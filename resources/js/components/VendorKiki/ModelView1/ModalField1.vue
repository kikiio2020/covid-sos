<template>
    <ValidationProvider :name="caption" :rules="rules" v-slot="validationContext">
        
        <div
            v-if="type=='div'"
            class="my-3 mr-3"
        >
            <div><strong>{{placeHolder}}:</strong> {{vModel}}</div>
        </div>  

        <b-form-input 
            v-if="type=='input'"
            v-model="vModel"
            @input="$emit('input', vModel)"
            :name="name"
            :id="id"
            :required="required"
            :placeholder="placeHolder + (required ? ' *': '')"
            class="my-3 mr-3"
            :state="getValidationState(validationContext)"
            aria-describedby="feedback"
            :disabled="disabled"
        ></b-form-input>
        
        <b-form-textarea
            v-if="type=='textarea'"
            v-model="vModel"
            @input="$emit('input', vModel)"
            :name="name"
            :id="id"
            :required="required"
            :placeholder="placeHolder + (required ? ' *': '')"
            rows="3"
            max-rows="6"
            class="my-3 mr-3"
            :state="getValidationState(validationContext)"
            aria-describedby="feedback"
            :disabled="disabled"
        ></b-form-textarea>

        <div v-if="type=='select'" class="my-3 mr-3">
            <div>{{ placeHolder + (required ? ' *': '') }}</div>
            <b-form-select
                v-model="vModel"
                @input="$emit('input', vModel)"
                :name="name"
                :id="id"
                :required="required"
                :disabled="disabled"
                class="mr-3"
                :state="getValidationState(validationContext)"
                aria-describedby="ariaDescribedby"
                :options="options"
            >
                <template v-slot:first>
                    <b-form-select-option value="" disabled>-- Please select an option --</b-form-select-option>
                </template>
            </b-form-select>
        </div>

        <div v-if="type=='selectRadioButtons'" class="my-3 mr-3">
            <div>{{ placeHolder + (required ? ' *': '') }}</div>
            <b-form-radio-group
                v-model="vModel" 
                @input="$emit('input', vModel)"
                :name="name"
                :id="id"
                :required="required"    
                :disabled="disabled"   
                :state="getValidationState(validationContext)"         
                aria-describedby="ariaDescribedby"
                buttons
                button-variant="outline-primary"
                size="lg"
                :options="options"
            ></b-form-radio-group>
        </div>

        <div v-if="type=='calendar'" class="my-3 mr-3">
            <div>{{placeHolder}}</div>
            <b-calendar 
                v-model="vModel" 
                @input="$emit('input', vModel)"
                :name="name"
                :id="id"
                :required="required"
                @context="onContext" 
                :min="dateMin" 
                :max="dateMax"
                :date-disabled-fn="dateDisabled" 
                locale="en-US"
                :state="getValidationState(validationContext)"
                aria-describedby="feedback"
                :disabled="disabled"
                label-help=""
            ></b-calendar>
        </div>

        <div  class="my-3 mr-3" v-if="type=='gMapLink'">
            <div>
                <strong>{{placeHolder}}:</strong>
                <a :href="'https://maps.google.com/?q=' + vModel" target="_blank">{{vModel}}</a>
            </div>
        </div>

        <model-chat-field
            v-if="type=='modelChat'"
            v-model="vModel"
            :api="api"
            :user-name="userName"
            :model-id="modelId"
            :name="name"
            :id="id"
            :place-holder="placeHolder"
            :caption="caption"
            :disabled="disabled"
        ></model-chat-field>

        <model-list-w-other-field
            v-if="type=='modelListWOther'"
            v-model="vModel"
            @input="$emit('select', vModel)"
            :name="name"
            :id="id"
            :required="required"
            :place-holder="placeHolder"
            :caption="caption"
            :options="options"
            :optionCaptions="optionCaptions"
            :optionPlaceHolders="optionPlaceHolders"
            class="my-3 mr-3"
            :state="getValidationState(validationContext)"
            aria-describedby="feedback"
            :disabled="disabled"
        ></model-list-w-other-field>

        <model-image-field
            v-if="type=='modelImage'"
            :name="name"
            :id="id"
            :place-holder="placeHolder"
            :caption="caption"
            :disabled="disabled"
            :api="api"
            :model-id="modelId"
            max-size="800"
        ></model-image-field>

        <model-shop-list-field
            v-if="type=='modelShoplist'"
            :name="name"
            :id="id"
            :place-holder="placeHolder"
            :caption="caption"
            :disabled="disabled"
            :api="api"
            :model-id="modelId"
        ></model-shop-list-field>

        

        <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>
</template>
<script>
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, image, size } from 'vee-validate/dist/rules';

extend('image', image);
extend('size', size);
extend('required', {
    ...required,
    message: "{_field_} is required"
});

export default {
    components: {ValidationProvider, ValidationObserver},
    props: {
        type: String, 
        value: {}, 
        name: String, 
        id: String,
        caption: String, 
        options: Array,
        optionCaptions: {},
        optionPlaceHolders: {},
        optionName: String,
        optionId: String,
        dateMin: Date,
        dateMax: Date,
        dateDisabled: Function,
        placeHolder: String, 
        disabled: {
            default: false
        }, 
        required: {
            default: false
        }, 
        rules: {
            default: ''
        },
        //previewImageSrc: String,
        api: String,
        userName: String,
        modelId: Number,
    },
    data() {
        return {
            vModel: this.value
        }
    },
    methods: {
        getValidationState({ dirty, validated, valid = null, invalid }) {
            return dirty || validated ? valid : null;
        },
        onContext(ctx) {
            this.context = ctx
        },
    },
    computed: {
    },
    mounted() {
    }
}
</script>