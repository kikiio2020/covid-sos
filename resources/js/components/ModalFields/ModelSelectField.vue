<template>
<div>
    <div>{{ placeholder }}</div>
    <b-form-select 
        v-bind="$attrs"
        v-model="vModel"
        @input="$emit('input', vModel)"
    >
        <template v-slot:first>
            <b-form-select-option value="null" disabled>-- Please select an option --</b-form-select-option>
        </template>
    </b-form-select>
</div>
</template>
<script>
export default {
    props: {
        value: Number,
        placeholder: String, 
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