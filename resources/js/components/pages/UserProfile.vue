<template>
    <div>
        <div class="container-fluid">
            <div class="page-header min-height-300 border-radius-xl mt-4">
                <span class="mask bg-gradient-primary opacity-6"></span>
            </div>
            <div class="card card-body shadow-blur mx-4 mt-n6">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div :class="['avatar avatar-xl position-relative',formState.errors.image ? 'border border-danger' : '']">
<!--                            <img src="" alt="profile-pic" class="w-100 border-radius-lg shadow-sm">-->
                            <img :src="authImage" :alt="formState.name + '-profile-pic'" class="w-100 border-radius-lg shadow-sm">
                            <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" @click="edit_image_button" id="edit-image-button">
                                <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1 text-capitalize">
                                {{ formState.name }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active shadow-lg" data-bs-toggle="tab" href="javascript:;"
                                       role="tab" aria-controls="overview" aria-selected="true">
<!--                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                                               fill-rule="evenodd">
                                                <g id="Rounded-Icons" transform="translate(-2319.000000, -291.000000)"
                                                   fill="#FFFFFF" fill-rule="nonzero">
                                                    <g id="Icons-with-opacity"
                                                       transform="translate(1716.000000, 291.000000)">
                                                        <g id="box-3d-50" transform="translate(603.000000, 0.000000)">
                                                            <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z" id="Path"></path>
                                                            <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" id="Path" opacity="0.7"></path>
                                                            <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" id="Path" opacity="0.7"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>-->
                                        <span class="ms-1">Personal Info</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Profile Information</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <form action="/user-profile" method="POST" role="form text-left" enctype="multipart/form-data" @submit.prevent="updateProfile">

                        <div v-for="(error, index) in formState.errors" :key="index" class="mt-3  alert alert-primary alert-dismissible fade show" role="alert" v-if="showProfileAlert && formState.errors">
                            <span class="alert-text text-white">
                            {{error}}</span>
                            <button type="button" class="btn-close" @click="showProfileAlert = false" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
<!--                        @if(session('success'))
                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">
                            {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="file" id="profile-image-input" @change="handleFileChange" class="d-none" accept="image/*">
                                    <label for="user-name" class="form-control-label">Full Name</label>
                                    <div >
                                        <input :class="['form-control',formState.errors.name ? 'border border-danger rounded-3' : '']" v-model="formState.name" type="text" placeholder="Name" id="user-name">

                                        <p v-if="formState.errors.name" class="text-danger text-xs mt-2">{{ formState.errors.name }}</p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user-email" class="form-control-label">Email</label>
<!--                                    <div class="@error('email')border border-danger rounded-3 @enderror">-->
                                    <div >
                                        <input :class="['form-control',formState.errors.email ? 'border border-danger rounded-3' : '']" v-model="formState.email" type="email" placeholder="@example.com" id="user-email">

                                        <p v-if="formState.errors.email" class="text-danger text-xs mt-2">{{ formState.errors.email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.phone" class="form-control-label">Phone</label>
                                    <div >
                                        <input :class="['form-control',formState.errors.phone ? 'border border-danger rounded-3' : '']" type="tel" placeholder="40770888444" id="number" v-model="formState.phone">

                                        <p v-if="formState.errors.phone" class="text-danger text-xs mt-2">{{ formState.errors.phone }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user.location" class="form-control-label">Location</label>
                                    <div >
                                        <input :class="['form-control', formState.errors.location ? 'border border-danger rounded-3' : '']" type="text" placeholder="Location" id="name" v-model="formState.location">

                                        <p v-if="formState.errors.location" class="text-danger text-xs mt-2">{{ formState.errors.location }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about">About Me</label>
                            <div >
                                <textarea :class="['form-control', formState.errors.about_me ? 'border border-danger rounded-3' : '']" id="about" rows="3" placeholder="Say something about yourself" v-model="formState.about_me">{{ formState.about_me }}</textarea>

                                <p v-if="formState.errors.about_me" class="text-danger text-xs mt-2">{{ formState.errors.about_me }}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Change Password</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <form action="/change-password" method="POST" role="form text-left" @submit.prevent="updatePassword">


                        <div v-for="(error, index) in formState.errors" :key="index" class="mt-3  alert alert-primary alert-dismissible fade show" role="alert" v-if="showPasswordAlert && formState.errors">
                            <span class="alert-text text-white">
                            {{error}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
<!--                        @if(session('success'))
                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">
                            {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                        @endif-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="current-password" class="form-control-label">Current Password</label>
<!--                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">-->
                                    <div class="">
                                        <input class="form-control" type="password" id="current-password" v-model="formState.password">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new-password" class="form-control-label">New Password</label>
                                    <div class="">
                                        <input class="form-control" type="password" id="new-password" v-model="formState.new_password">
<!--                                        @error('new_password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="new-password" class="form-control-label">Confirm Password</label>
                                    <div class="">
                                        <input class="form-control" type="password" id="new-password-confirmation" v-model="formState.new_password_confirmation">
<!--                                        @error('new_password_confirmation')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--    Modal for update image -->
        <div v-if="updateImageModel" :class="['modal', updateImageModel ? 'd-block' : 'd-none' ]" id="updateImageModel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importInvoiceModelLabel">Update Image</h5>
                        <button type="button" class="btn-close bg-dark" @click="updateImageModelClose">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="importInvoiceForm" enctype="multipart/form-data" @submit.prevent="updateImageForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
<!--                                        <input type="file" class="" id="invoice_file" @change="handleImageUpdate">-->
                                        <img :src="updateImagePrev" alt="">
                                        <input type="file" id="profile-image-input" @change="handleFileChange" accept="image/*">
                                        <small v-if="formState.errors.image" class="error text-danger">
                                            {{ formState.errors.image }}
                                        </small>
                                    </div>
                                </div>

                            </div>
                            <div>
                                <button type="submit" id="submitHandleImageBtn" class="btn bg-gradient-success">Update</button>

                                <div id="loader" style="display: none; margin-left: 10px;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref, reactive, computed, watch} from 'vue';

const auth = ref([]);
const formState = reactive({
   image  : '',
   name  : '',
   email  : '',
   password  : '',
    phone  : '',
    new_password  : '',
    new_password_confirmation  : '',
   location  : '',
   about_me  : '',
    errors : {}
});


const showProfileAlert = ref(false);
const showPasswordAlert = ref(false);
const updateImageModel = ref(false);

const updateImagePrev = ref('');

const fetchAuth = async () => {
    await axios.get('/api/user_profile')
        .then(response=>{
            formState.image = response.data.auth.image;
            formState.name = response.data.auth.name;
            formState.email = response.data.auth.email;
            formState.password = response.data.auth.password;
            formState.phone = response.data.auth.phone;
            formState.location = response.data.auth.location;
            formState.about_me = response.data.auth.about_me;
            auth.value = response.data.auth

        }).catch(error=>{
            console.error(error);
        })
}

const authImage = computed(() => {
    return formState.image ? asset(formState.image) : asset('./assets/img/bruce-mars.jpg');
});

const asset = (path) => {
    return `${window.location.origin}/${path}`;
};

onMounted(()=>{
    fetchAuth();
})

const updateImageForm = async () => {
    await axios.post('/api/update_profile_image',{
        image: formState.image,
    },{
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    }).then(response=>{
        updateImageModel.value = false;
        fetchAuth();
    }).catch(error=>{
        if (error.response.status == 422) {
            formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                acc[field] = error.response.data.errors[field][0]; // Get the first error message
                return acc;
            }, {});
        } else {
            formState.errors = {
                general: 'An unexpected error occurred. Please try again later.'
            };
        }
        // showProfileAlert.value = true;
    })
}

const updateProfile = async () => {
    // let imageName = formState.image.split('/').pop();

    await axios.post('/api/update_profile', {
        // image: formState.image,
        name: formState.name,
        email: formState.email,
        phone: formState.phone,
        location: formState.location,
        about_me: formState.about_me,
    },{
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    }).then(response=>{
        console.log(response.data);
    }).catch(error=>{
        if (error.response.status == 422) {
            formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                acc[field] = error.response.data.errors[field][0]; // Get the first error message
                return acc;
            }, {});
        } else {
            formState.errors = {
                general: 'An unexpected error occurred. Please try again later.'
            };
        }
        showProfileAlert.value = true;
    })
}

const updatePassword = () => {
    axios.post('/api/update_password', {
        password: formState.password,
        new_password: formState.new_password,
        new_password_confirmation: formState.new_password_confirmation,
    }).then(response=>{
        console.log(response.data);
        showPasswordAlert.value = false;
    }).catch(error=>{
        if (error.response.status == 422) {
            formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                acc[field] = error.response.data.errors[field][0]; // Get the first error message
                return acc;
            }, {});
        } else {
            formState.errors = {
                general: 'An unexpected error occurred. Please try again later.'
            };
        }
        showPasswordAlert.value = true;
    })
}

const edit_image_button = () => {
    updateImageModel.value = true;
}

const updateImageModelClose = () => {
    updateImageModel.value = false;
    fetchAuth();
}

const handleFileChange = (event) => {
    const file = event.target.files[0]; // Get the selected file
    if (file) {
        formState.image = file; // Update the form state
        updateImagePrev.value = URL.createObjectURL(file); // Generate a preview URL
    }
}

const handleImageUpdate = () => {
    //
}

</script>

<style scoped>

</style>
