<template>
<div>
    <ValidationProvider :name="caption" :rules="rules" v-slot="validationContext">
        <b-form-group id="formgroup1">
            <b-form-input
                v-model="searchText"    
                @focus="onInputFocus"
                @input="onInputFocus"
                @blur="onInputBlur"
                
                :placeholder="placeholder"
                :disabled="disabled || loadingOptions"
                
                :required="required"
                aria-describedby="feedback"
                :state="getValidationState(validationContext)"
            ></b-form-input>

            <div 
                style="overflow-y: scroll; height:10em;"
                class="list-group shadow"
                v-show="showSelect"
            >
                <a 
                    v-for="(item, key) in filteredOptions" :key="key"
                    href="#"
                    class="list-group-item list-group-item-action"
                    @click="onItemSelect(item)"
                >{{item.text}}</a>
                
            </div>
        </b-form-group>
    
        <b-form-invalid-feedback id="feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>
</div>
</template>
<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
    ...required,
    message: "{_field_} is required"
});

export default {
    components: {VueBootstrapTypeahead, ValidationProvider, ValidationObserver},
    props: [
        'value',
        'name',
        'id',
        'placeholder',
        'caption',
        'disabled',
        'modelId',
        'required',
        'api',
        'rules',
    ],
    data() {
        return {
            vModel: this.value,
            searchText: '',
            showSelect: false,
            loadingOptions: false,
            options: [],
        }
    },
    methods: {
        onInputFocus(target) {
            if (this.searchText) {
                this.vModel = '';
            }
            this.showSelect = true;
        },
        onInputBlur() {
            const selectedValue = this.getOptionValueByText(this.searchText);
            if (selectedValue) {
                this.vModel = selectedValue;
            } else {
                this.vModel = '';
                //this.searchText = '';
            }
        },

        onItemSelect(item) {
            this.searchText = item.text;
            this.vModel = item.value;
            this.showSelect = false;
            this.$emit('input', this.vModel);
        },

        getShoplistItemOptions() {  
            this.loadingOptions = true;
            axios.get('webapi/item').then( response => {
                //this.$refs.shoplistItemView.modalFields[0].options = response.data.data;
                this.options = response.data.data;
                if (this.vModel) {
                    this.searchText = this.getOptionTextByValue(this.vModel)
                }
                this.loadingOptions = false;
            }).catch(err => {
                this.options = [];
                this.searchText = '';
                this.loadingOptions = false;
            });
        },

        getOptionTextByValue(value) {
            const result = this.options.find(item => {
                    return value === item.value;
                });
            return result ? result.text : '';
        },

        getOptionValueByText(text) {
            const result = this.options.find(item => {
                    return text.toLowerCase() === item.text.toLowerCase();
                });
            return result ? result.value : 0;
        },

        getValidationState({ dirty, validated, valid = null, invalid }) {  
            return dirty || validated ? valid : null;
        },
    },
    computed: {
        filteredOptions: function() {
            return this.options.filter(
                item => {
                    const itemMatches = item.text.toLowerCase()
                        .includes(
                            this.searchText.toLowerCase()
                        );
                    
                    return itemMatches;
                }
            )    
        },
    
    },
    mounted() {
        this.getShoplistItemOptions();
    },
}
</script>