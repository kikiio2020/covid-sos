<template>
<div>
<b-container>
    <b-row><b-col>
        <h3>Edit Profile</h3>
    </b-col></b-row>

    <!--Profile Update-->
    <b-row><b-col class="col-8">
    <ValidationObserver ref="formObserver" v-slot="formContext">
        <b-form  ref="form" v-on="getFormValidationState(formContext)" >
            <b-container class="col-8 my-3">
                <b-row>
                    <b-col class="text-right">
                        <label  for="input-group-1">Name:</label>
                    </b-col>
                    <b-col class="text-left">
                        <ValidationProvider name="Name" rules="max:255" v-slot="validationContext">
                            <b-form-group
                                id="input-group-name"
                                label-for="input-name"
                            >
                                <b-form-input
                                    id="input-name"
                                    name="Name"
                                    v-model="profile.name"
                                    placeholder="Name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="feedback-input-name"
                                ></b-form-input>
                            <b-form-invalid-feedback id="feedback-input-name">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-right">
                        <label  for="input-group-1">Email:</label>
                    </b-col>
                    <b-col class="text-left">
                        {{ profile.email }}
                        <small><b-icon-info-circle 
                            class="mx-2 text-primary"
                            v-b-popover.hover.right="{
                                variant: 'primary',
                                content: 'We use email as your identification and cannot be changed once registered.',
                            }" 
                        ></b-icon-info-circle></small>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-right">
                        <label  for="input-group-1">Address:</label>
                    </b-col>
                    <b-col class="text-left">
                        {{ profile.address }}
                        <small><b-icon-info-circle 
                            class="mx-2 text-primary"
                            v-b-popover.hover.right="{
                                variant: 'primary',
                                content: 'We use your address to determine the request you receive or sent out therefore cannot be changed once registered. It is also recommeneded during the crisis the population minimize movements',
                            }" 
                        ></b-icon-info-circle></small>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="text-right">
                    </b-col>
                    <b-col class="text-left">
                        <b-button variant="primary" @click="onSave" class="my-3" :disabled="saving">
                            <b-spinner v-if="saving" label="Spinning"></b-spinner>
                            <span v-else>Save</span>
                        </b-button>
                    </b-col>
                </b-row>
            </b-container>
        </b-form>
    </ValidationObserver>
    </b-col></b-row>

    <!--Status Update-->
    <!-- 
    <b-row><b-col class="col-8">    
        <b-container class="col-8">
            <b-row>
                <b-col class="text-right">
                    <label  for="input-group-1">Status:</label>
                </b-col>
                <b-col class="text-left">
                    {{ profile.statusCapArr[profile.status].caption }}
                    <small><b-icon-info-circle 
                        class="mx-2 text-primary"
                        v-b-popover.hover.right="{
                            variant: 'primary',
                            content: profile.statusCapArr[profile.status].description,
                        }" 
                    ></b-icon-info-circle></small>
                </b-col>
            </b-row>
            <b-row>
                <b-col class="text-right">
                </b-col>
                <b-col class="text-left">
                    <b-button variant="primary" @click="onChangeStatus" :disabled="savingStatus">Change Status</b-button>
                </b-col>
            </b-row>
        </b-container>
    </b-col></b-row>
 	-->
 
 	<!-- Hujo Account -->
 	<b-row><b-col class="col-8">    
        <b-container class="col-8">
            
            <b-row>
                <b-col class="text-right">
                </b-col>
                <b-col class="text-left">
                    <b-button variant="primary" href="/hujoCoin">Hujo Account</b-button>
                </b-col>
            </b-row>
        </b-container>
    </b-col></b-row>
 
    <!--Change Password-->
    <b-row><b-col class="col-8">
        <ValidationObserver ref="formObserver" v-slot="passwordFormContext">
            <b-form  ref="passwordForm" v-on="getFormValidationState(passwordFormContext)" >
                <b-container class="col-8 my-3">
                    <b-row>
                        <b-col class="text-right">
                            <label  for="input-group-1">Password:</label>
                        </b-col>
                        <b-col class="text-left">
                            <ValidationProvider name="Password" rules="required|password:@Confirm|min:8" v-slot="validationContext">
                                <b-form-group
                                    id="input-group-password"
                                    label-for="input-password"
                                >
                                    <b-form-input
                                        id="input-password"
                                        name="Password"
                                        type="password"
                                        v-model="profile.password"
                                        placeholder="Password"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="feedback-input-password"
                                    ></b-form-input>
                                    <b-form-invalid-feedback id="feedback-input-password">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col class="text-right">
                            <label  for="input-group-1">Confirm Password:</label>
                        </b-col>
                        <b-col class="text-left">
                            <ValidationProvider name="Confirm" rules="required" v-slot="validationContext">
                                <b-form-group
                                    id="input-group-confirm"
                                    label-for="input-confirm"
                                >
                                    <b-form-input
                                        id="input-confirm"
                                        name="Confirm"
                                        type="password"
                                        v-model="profile.confirm"
                                        placeholder="Confirm Password"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="feedback-input-confirm"
                                    ></b-form-input>
                                    <b-form-invalid-feedback id="feedback-input-confirm">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>
                    </b-row>
                    
                    <b-row>
                        <b-col class="text-right">
                        </b-col>
                        <b-col class="text-left">
                            <b-button variant="primary" @click="onChangePassword" class="my-3" :disabled="savingPassword">
                                <b-spinner v-if="savingPassword" label="Spinning"></b-spinner>
                                <span v-else>Change Password</span>
                            </b-button>
                        </b-col>
                    </b-row>
                </b-container>
            </b-form>
        </ValidationObserver>
    </b-col></b-row>

    </b-col></b-row>
</b-container>
</div>
</template>
<script>
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, password, max, min } from 'vee-validate/dist/rules';

extend('required', {
    ...required,
    message: "{_field_} is required"
});
extend('max', {
    ...max,
    message: "{_field_} must be less than {length} characters"
});
extend('min', {
    ...min,
    message: "{_field_} must be longer than {length} characters"
});
extend('password', {
    params: ['target'],
    validate(value, { target }) {
        return value === target;
    },
    message: 'Password confirmation does not match'
});

export default {
    components: {ValidationProvider, ValidationObserver},
    props: ['userProfile', 'statusCaptions'],
    data() {
        return {
            formInvalid: false,
            profile: {
                ...this.userProfile, 
                ...{statusCapArr: this.statusCaptions}
            },
            statusCapArr: this.statusCaptions,
            saving: false,
            savingStatus: false,
            savingPassword: false,
        }
    },
    methods: {
        getValidationState({ dirty, validated, valid = null, invalid, errors }) {
            return dirty || validated ? valid : null;
        },
        getFormValidationState({ invalid }) {
            this.formInvalid = invalid;                
        },
        onSave() {
            this.saving = true;
            const data = {
                name: this.profile.name,
            };
            axios.put('/webapi/user', data)
                .then((response) => {
                    console.log(response);
                    this.saving=false;
                    this.$root.$bvToast.toast('Profile Update Successful', {
                        title: 'Profile Update',
                        variant: 'success',
                    });
                }).catch((error) => {
                    console.log(error);
                    this.saving=false;
                    this.$root.$bvToast.toast('Profile Update Failed', {
                        title: 'Profile Update',
                        variant: 'danger',
                    });
                }); 
        },
        onChangeStatus() {
            this.$bvModal.msgBoxConfirm('This will reset your account and will prompt you for the screening questions. Please click \'Yes\' to continue.', 
            {
                title: 'Change Status Confirmation',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'primary',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            }).then(confirmed => {
                if (confirmed) {
                    const data = {
                        status: 0
                    }
                    axios.put('/webapi/user/status', data)
                    .then((response) => {
                        console.log(response);
                        this.saving=false;
                        window.location = "/home";
                    }).catch((error) => {
                        console.log(error);
                        this.saving=false;
                        this.$root.$bvToast.toast('Status Update Failed', {
                            title: 'Status Update',
                            variant: 'danger',
                        });
                    }); 
                }
            }).catch(err => {})
        },
        onChangePassword() {
            this.savingPassword = true;
            axios.put('/webapi/user/password', {
                password: this.profile.password,
                password_confirmation: this.profile.confirm,
            }).then((response) => {
                    console.log(response);
                    this.savingPassword=false;
                    this.$root.$bvToast.toast('Change Password Successful', {
                        title: 'Change Password',
                        variant: 'success',
                    });
                }).catch((error) => {
                    console.log(error);
                    this.saving=false;
                    this.$root.$bvToast.toast('Change Password Failed', {
                        title: 'Change Password',
                        variant: 'danger',
                    });
                }); 
        },
        
    },
    computed: {},
    mounted() {
    }
}
</script>