<template>
    <div>
        <b-container>
            <b-row>
                <b-col sm="6" class="text-left">
                    <h2>{{tableName}}</h2>
                </b-col>
                <b-col sm="6" class="text-right">
                    <b-input-group size="sm">
                        <b-input-group-prepend v-if="insertable">
                            <b-button class="mr-3" ref="insertModelButton" @click="insertModel($event.target)"><b-icon-plus></b-icon-plus></b-button>
                        </b-input-group-prepend>
                        <b-form-input
                            v-if="searchable"
                            v-model="filter"
                            type="search"
                            id="filterInput"
                            placeholder="Type to Search"
                        ></b-form-input>
                        <b-input-group-append v-if="searchable">
                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group size="sm">
                </b-col>
            </b-row>
        </b-container>
        <!--:items="dataProvider"-->
        <b-table
            id="grid-table"
            ref="gridTable"
            :items="items"
            :fields="gridFields"
            :filter="filter"
            :per-page="rowPerPage"
            :current-page="currentPage"
            v-bind="tableProperties"
            v-b-popover="tableProperties.vBPopover"
            @row-selected="onRowSelected"
        >
            <template v-slot:cell(actions)="row">
                <b-icon-pen v-if="editable" class="button" @click="editModel(row.item, $event.target)"></b-icon-pen>
                <b-icon-trash v-if="deletable" class="button" @click="confirmRemoveModel(row.item.id)"></b-icon-trash>
            </template>   
        </b-table>
        <b-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="rowPerPage"
            aria-controls="grid-table"
            align="right"
        ></b-pagination>
        <!-- Edit modal -->
        <b-modal :id="id" :size='modalSize' :title="editModalTitle" ok-title="Save" @hide="resetEditModal();loadData();" no-close-on-backdrop>
            <ValidationObserver ref="formObserver" v-slot="formContext">
                <b-form ref="form" v-on="getFormValidationState(formContext)" @submit.stop.prevent="handleSubmit">
                    
                    
                    <div v-for="(modalField, key) in modalFields">
                        <modal-field
                            v-if = "!modalField.requireModel || editModalRecord.id > 0"
                            :modal-field-properties="modalField"
                            v-model="editModalRecord[modalField.id]"
                            :disabled="saving"
                            :model-id="editModalRecord.id"
                            :user-name="userName"
                            :api="api"
                        ></modal-field>
                    </div>

                    
                </b-form>
            </ValidationObserver>
            <template v-slot:modal-footer>
                <div class="w-100 text-right">
                    <b-button 
                        variant="secondary"
                        @click="handleClose"
                    >Close</b-button>
                    <b-button
                        variant="primary"
                        @click="handleSave"
                        :disabled="saving || formInvalid"
                    >
                        <b-spinner v-if="saving" label="Spinning"></b-spinner>
                        <span v-else>Save</span>
                    </b-button>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { BTable, BForm, BFormInput, BModal, BFormTextarea, BFormFile, BFormInvalidFeedback } from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, image, size } from 'vee-validate/dist/rules';

extend('image', image);
extend('size', size);
extend('required', {
    ...required,
    message: "{_field_} is required"
});

export default {
    components: {BTable, ValidationProvider, ValidationObserver},
    props: {
        'id': String,
        'tableName': String,
        'gridFields': {},
        'modalFields': {},
        'initialValues': {
            default: {
                id: 0, //must have ID for any <model-*> fields to work
            }
        },
        'modalSize': {
            default: 'md'
        },
        'api': String,
        'userName': String,
        'gridUrlQuery': '',
        'perPage': {
            default: 20
        },
        'searchable': {
            default: true
        },
        'insertable': {
            default: true
        },
        'editable': {
            default: true
        },
        'deletable': {
            default: true
        },
        'tableProperties': {
            type: Object,
            default: () => ({
                vBPopover: '',
            })
        },
    },
    data() {
        return {
            filter: '',
            items: [],
            editModalRecord: this.initialValues,
            originalModalRecord: JSON.parse(JSON.stringify(this.initialValues)),
            editModalTitle: '',
            editModalNew: false,
            formInvalid: true,
            rowPerPage: this.perPage,
            currentPage: 1,
            totalRows: 0,
            tableBusy: false,
            saving: false,
            rowsSelected: [],
        }
    },
    methods: {
        insertModel(button, modalRecordData) {
            if (modalRecordData) {
                this.editModalRecord = { ...this.editModalRecord, ...modalRecordData }
            } 
            this.editModalTitle = 'Add New ' + this.tableName;
            this.editModalNew = true;
            this.$root.$emit('bv::show::modal', this.id, button);
        },
        editModel(item, button) {
            this.editModalRecord = item;
            //this.setOriginalModalRecord(item);
            this.editModalTitle = 'Edit '+ this.tableName;
            this.editModalNew = false;
            this.$root.$emit('bv::show::modal', this.id, button);
        },
        setOriginalModalRecord(original) {
            if (!original) {
                //console.log(this.originalModalRecord);
                return this.originalModalRecord;
            }
            this.originalModalRecord = { ...original };

            return this.originalModalRecord;
        },
        confirmRemoveModel(id) {
            this.$bvModal.msgBoxConfirm('Remove ' + this.tableName + '?')
                .then(value => {
                    this.removeModel(id);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        removeModel(id) {
            axios.delete(this.api + '/' + id)
                .then((response) => {
                    this.$root.$bvToast.toast('Successfully removed ' + this.tableName, {
                        title: 'Remove ' + this.tableName,
                        variant: 'success',
                    });
                    this.loadData();
                    //this.$root.$emit('bv::refresh::table', 'grid-table');
                }).catch((error) => {
                    console.log(error);
                    this.$root.$bvToast.toast('Remove ' + this.tableName + ' failed', {
                        title: 'Remove ' + this.tableName,
                        variant: 'danger',
                    });
                });
        },
        resetEditModal() {
            this.editModalRecord = this.setOriginalModalRecord();
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            return valid
        },
        handleClose(){
            this.$root.$emit('bv::hide::modal', this.id);
            //this.$root.$emit('bv::refresh::table', 'grid-table');
        },
        handleSave(bvModalEvt) {
            bvModalEvt.preventDefault();
            if (this.finalValidation()) {
                this.handleSubmit();
            }
        },
        finalValidation() {
            try {
                this.modalFields.forEach((field, index) => {
                    if (field.required && !this.editModalRecord[field.id]) {
                        throw 'Saving Failed: ' + field.name + ' field must be valid value.';
                    }
                });
            } catch (errMsg) {
                this.$bvToast.toast(errMsg, {
                    title: 'Saving ' + this.tableName,
                    variant: 'danger',
                    solid: false
                });
                return false;
            }
            
            return true;
        },
        handleSubmit() {           
            this.saving=true;
            /*let formData = new FormData();
            Object.keys(this.editModalRecord).forEach(key => {
                if (this.editModalRecord[key]) {
                    formData.append(key, this.editModalRecord[key]);
                } 
            });*/
            var formData = this.editModalRecord;
            if (this.editModalNew) {
                var verb = 'POST';
                var apiUrl = this.api;
                var toasterTitle = 'Insert New ' + this.tableName;
                var successMessage = 'New '+ this.tableName + ' Successfully Created!';
                var failMessage = 'New ' + this.tableName + ' Creation Failed! Please try again or contact the site admin.';
            } else {
                var verb = 'PUT';
                var apiUrl = this.api + '/' + this.editModalRecord.id;
                var toasterTitle = 'Update ' + this.tableName;
                var successMessage = this.tableName + ' Successfully Updated!';
                var failMessage = this.tableName + ' Update Failed! Please try again or contact the site admin.';
            }

            const options = {
                method: verb,
                //headers: { 'content-type': 'multipart/form-data' },
                data: formData,
                url: apiUrl
            };
            axios(options)
                .then((response) => {
                    this.saving=false;
                    if (!this.editModalNew || !this.hasRequireModelField) {
                        this.$root.$emit('bv::hide::modal', this.id);
                    }
                    this.editModalRecord.id = response.data.id;
                    this.editModalNew = false;
                    
                    //this.$root.$emit('bv::refresh::table', 'grid-table');
                    //this.loadData();
                    this.$root.$bvToast.toast(successMessage, {
                        title: toasterTitle,
                        variant: 'success',
                    });
                }).catch((error) => {
                    console.log(error);
                    this.saving=false;
                    this.$root.$bvToast.toast(failMessage, {
                        title: toasterTitle,
                        variant: 'danger',
                    });
                });
        },
        getValidationState({ dirty, validated, valid = null, invalid }) {
            return dirty || validated ? valid : null;
        },
        getFormValidationState({ invalid }) {
            this.formInvalid = invalid;                
        },
        setGridUrlQuery(urlQuery) {
            this.gridUrlQuery = urlQuery;
        },
        loadData() {
            axios.get(this.api + this.gridUrlQuery).then(response => {
                this.totalRows = response.data.data.length;
                //this.totalRows = response.data.length;
                this.tableBusy = false;
                this.items = response.data.data;
                this.$root.$emit('bv::refresh::table', 'grid-table');
            }).catch(error => {
                console.log(error);
                this.tableBusy = false;
                this.items;
                this.$root.$bvToast.toast('Error loading table', {
                    title: 'Loading ' + this.tableName,
                    variant: 'danger',
                });
            });
        },
        getPreviewImageSrc (modalField, id) {
            return modalField.filePath && !this.editModalNew ? modalField.filePath.replace('[id]', id) : '';
        },
        onContext(ctx) {
            this.context = ctx
        },
        onRowSelected(item) {
            this.rowsSelected = item;
        }
    },
    computed: {
        hasRequireModelField: function() {
            const modelField = this.modalFields.find((modelField) => {
                return modelField.requireModel;
            })
            
            return modelField && modelField.requireModel;
        },
    },
    mounted() {
        this.loadData();
    }
}    
</script>

<style>
.button {
    cursor:pointer;
}
</style>
