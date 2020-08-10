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
                            <b-button class="ml-3 mr-3" id="insertBtn" ref="insertModelButton" @click="insertModel($event.target)"><b-icon-plus></b-icon-plus></b-button>
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
                <b-dropdown no-caret variant="primary" size="sm">
					<template v-slot:button-content>
				    	<b-icon-caret-down></b-icon-caret-down>
				    </template>
					<b-dropdown-item 
						v-for="(action, index) in actions"
						@click="rowAction(action, row, $event.target)"
						:key="row.item.id + index"
						variant="primary"
						:disabled="action.disabled && action.disabled(row)"
					>
						<div v-if="'edit'==action" ref="editDropdownItem"><b-icon-pen></b-icon-pen> Edit</div>
                		<div v-else-if="'details'==action" ref="detailsDropdownItem"><b-icon-card-list></b-icon-card-list> Details</div>
                		<div v-else-if="'remove'==action" ref="removeDropdownItem"><b-icon-trash></b-icon-trash> Delete</div>
                		<div v-else>
                			<b-icon v-if="action.icon" :icon="action.icon"></b-icon> 
                			<i v-if="action.fontawesome" :class="action.fontawesome"></i>
                			{{action.label}}
               			</div>
					</b-dropdown-item>
  				</b-dropdown>
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
        <b-modal ref="modal" :id="id" :size='modalSize' :title="editModalTitle" ok-title="Save" @hide="handleModalHide" no-close-on-backdrop>
            <ValidationObserver ref="formObserver" v-slot="formContext">
                <b-form ref="form" v-on="getFormValidationState(formContext)" @submit.stop.prevent="handleSubmit">
                	<modal-field-2
                        v-for="(modalField, key) in visibleModalFields"
                        v-bind:key="modalField.id"
                        ref="modalField"
                        :modal-field-properties="modalField"
                        v-model="editModalRecord[modalField.id]"
                        :disabled="saving"
                        :model-id="editModalRecord.id"
                        :user-name="userName"
                        :api="api"
                    ></modal-field-2>
                </b-form>
            </ValidationObserver>
            <template v-slot:modal-footer>
                <div class="w-100 text-right">
                    <b-button 
                    	v-for="(button, key) in buttons" 
                    	:key="button.id" 
                    	v-bind="button"
                    	@click="$emit(button.event, editModalRecord)"
                   	>{{button.content}}</b-button>
                    <b-button 
                        variant="secondary"
                        @click="handleClose"
                    >Close</b-button>
                    <b-button
                        v-if="!editModalReadOnly"
                        variant="primary"
                        @click="handleSave"
                        :disabled="saving || formInvalid"
                        ref="saveBtn"
                    >
                        <b-spinner v-if="saving" label="Spinning"></b-spinner>
                        <span v-else>Save</span>
                    </b-button>
                </div>
            </template>
        </b-modal>
        
        <!-- Remove Confirm Box -->
        <b-modal ref="removeConfirmBox" id="removeConfirmBox" title="Confirm Remove" no-close-on-backdrop>
        	<p class="my-4">Remove {{tableName}}?</p>
        	<template v-slot:modal-footer>
                <div class="w-100 text-right">
                    <b-button 
                        variant="secondary"
                        @click="removeCancel"
                    >Cancel</b-button>
                    <b-button
                        variant="primary"
                        @click="removeModel(removeConfirmRowId);removeConfirmRowId=0;$root.$emit('bv::hide::modal','removeConfirmBox');"
                        ref="removeBtn"
                    >OK</b-button>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { BTable, BForm, BFormInput, BModal, BFormTextarea, BFormFile, BFormInvalidFeedback } from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/en.json';
Object.keys(rules).forEach(rule => {
	extend(rule, {
    	...rules[rule],
	    message: messages[rule],
  	});
});

export default {
    components: {
    	BTable, 
    	ValidationProvider, 
    	ValidationObserver,
   	},
    props: {
        'id': String,
        'tableName': String,
        'gridFields': {},
        'actions': {},
        'buttons': {},
        'modalFields': Array,
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
            editModalRecord: JSON.parse(JSON.stringify(this.initialValues)),
            originalModalRecord: JSON.parse(JSON.stringify(this.initialValues)),
            editModalTitle: '',
            editModalNew: false,
            editModalReadOnly: false,
            formInvalid: true,
            rowPerPage: this.perPage,
            currentPage: 1,
            totalRows: 0,
            tableBusy: false,
            saving: false,
            rowsSelected: [],
            removeConfirmRowId: 0,
        }
    },
    methods: {
        rowAction(action, row, eventTarget) {
        	if (typeof this[action] === 'function') {
        		this[action](row, eventTarget);
       		} else {
     			this.$emit(action.event, row.item);
       		}	
        },
        details(row, button) {
        	this.editModalRecord = row.item;
            this.editModalTitle = 'Details '+ this.tableName;
            this.editModalReadOnly = true;
            this.$root.$emit('bv::show::modal', this.id, button);
        },
        insertModel(button, modalRecordData) {
            if (modalRecordData) {
                this.editModalRecord = { ...this.editModalRecord, ...modalRecordData }
            } 
            this.editModalTitle = 'Add New ' + this.tableName;
            this.editModalNew = true;
            this.editModalReadOnly = false;
            this.$root.$emit('bv::show::modal', this.id, button);
        },
        edit(row, button) {
            this.editModalRecord = row.item;
            this.editModalTitle = 'Edit '+ this.tableName;
            this.editModalNew = false;
            this.editModalReadOnly = false;
            this.$root.$emit('bv::show::modal', this.id, button);
        },
        remove(row) {
            this.removeConfirmRowId = row.item.id;
            this.$root.$emit('bv::show::modal', 'removeConfirmBox');
          	/* This is changed to custom modal becase there seems no way to mock for test 
          	* this.$bvModal.msgBoxConfirm('Remove ' + this.tableName + '?')
                .then(value => {
                    if (value) {
                    	this.removeModel(row.item.id);	
                    }
                })
                .catch(err => {
                    console.log(err);
                });
            */
        },
        removeCancel() {
        	this.$root.$emit('bv::hide::modal', 'removeConfirmBox');
        	this.removeConfirmRowId = 0;
        },
        removeModel(id) {
            axios.delete(this.api + '/' + id)
                .then((response) => {
                    this.$root.$bvToast.toast('Successfully removed ' + this.tableName, {
                        title: 'Remove ' + this.tableName,
                        variant: 'success',
                    });
                    this.loadData();
                    this.$emit('record-removed', id);
                    this.$emit('async-returns', id);
                }).catch((error) => {
                    /*
                    this.$root.$bvToast.toast('Remove ' + this.tableName + ' failed', {
                        title: 'Remove ' + this.tableName,
                        variant: 'danger',
                    });
                    this.$emit('record-remove-failed', id);
                    */
                    this.$emit('record-remove-failed', error);
                    this.$emit('async-returns', id);
                });
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            return valid
        },
        handleClose(){
        	this.$root.$emit('bv::hide::modal', this.id);
        },
        handleModalHide() {
        	//triggered by modal hide
        	this.resetEditModal();
        	this.loadData();
        	this.$emit('modal-close');
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
            var formData = this.editModalRecord;
            if (this.editModalNew) {
            	var verb = 'POST';
                var apiUrl = this.api;
                var toasterTitle = 'Insert New ' + this.tableName;
                var successMessage = 'New '+ this.tableName + ' Successfully Created!';
                var failMessage = 'New ' + this.tableName + ' Creation Failed! Please try again or contact the site admin.';
                var successEvent = 'record-created';
                var failEvent = 'record-create-failed';
            } else {
                var verb = 'PUT';
                var apiUrl = this.api + '/' + this.editModalRecord.id;
                var toasterTitle = 'Update ' + this.tableName;
                var successMessage = this.tableName + ' Successfully Updated!';
                var failMessage = this.tableName + ' Update Failed! Please try again or contact the site admin.';
                var successEvent = 'record-updated';
                var failEvent = 'record-update-failed';
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
                	this.$root.$bvToast.toast(successMessage, {
                        title: toasterTitle,
                        variant: 'success',
                    });
                    this.$emit(successEvent, response.data.id);
                    this.$emit('async-returns', response.data.id);
                    if ( 
                   		!this.hasRequireModelField
                   		|| !this.editModalNew 
                  	) {
                    	this.resetEditModal();
                    	this.$root.$emit('bv::hide::modal', this.id);
                    	return;
                    }
                    this.editModalRecord.id = response.data.id;
                    this.editModalNew = false;
                }).catch((error) => {
                    this.saving=false;
                    /*
                    this.$root.$bvToast.toast(failMessage, {
                        title: toasterTitle,
                        variant: 'danger',
                    });
                    this.$emit(failEvent, this.editModalRecord.id);
                    */
                    this.$emit(failEvent, error);
                    this.$emit('async-returns', this.editModalRecord.id);
                });
        },
        resetEditModal() {
        	this.editModalRecord = JSON.parse(JSON.stringify(this.originalModalRecord));
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
                this.tableBusy = false;
                this.items = response.data.data;
                this.$root.$emit('bv::refresh::table', 'grid-table');
            }).catch(error => {
                this.tableBusy = false;
                this.items;
                /*
                this.$root.$bvToast.toast('Error loading table', {
                    title: 'Loading ' + this.tableName,
                    variant: 'danger',
                });
                */
                this.$emit('record-load-failed', error);
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
        },
    },
    computed: {
        hasRequireModelField: function () {
        	const modalField = this.modalFields.find(modalField => {
        		if (!modalField.requireModel) {
        			return false;
        		}
        		if (!('conditions' in modalField)) {
           			return true;
               	}
        		
        		return Object.entries(modalField.conditions).reduce(
       				(result, entry) => {
       					return (result || this.editModalRecord[entry[0]] == entry[1]);
       				},
       				false
      			)
        	});
        	
        	return modalField !== undefined;
        },
        visibleModalFields: function () {
        	return this.modalFields.filter(modalField => {
        		if (modalField.requireModel && this.editModalRecord.id <= 0) {
        			return false;
        		}
        		if (!('conditions' in modalField)) {
           			return true;
               	}
        		
        		return Object.entries(modalField.conditions).reduce(
       				(result, entry) => {
       					return (result || this.editModalRecord[entry[0]] == entry[1]);
       				},
       				false
      			)
        	});
        }
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
