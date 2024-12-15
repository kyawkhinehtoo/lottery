<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!--All Card-->
            <div class="grid gap-2 md:gap-6 grid-cols-2 lg:grid-cols-5 py-6">
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div
                        class="px-3 py-2 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <i class="fa fa-hand-holding-dollar"></i>
                    </div>
                    <div class="ml-2">
                        <p class="mb-2 text-sm font-bold text-gray-600 dark:text-gray-400">Today Sale Amount</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{
                            formatNumber(todaySaleAmount) }}
                            MMK</p>
                    </div>
                </div>
                <!-- End Card -->
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div
                        class="px-3 py-2 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <i class="fa fa-chart-line "></i>
                    </div>
                    <div class="ml-2">
                        <p class="mb-2 text-sm font-bold text-gray-600 dark:text-gray-400">Today Sale Numbers</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ todaySaleNumber }}</p>
                    </div>
                </div>
                <!-- End Card -->
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div
                        class="px-3 py-2 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                        <i class="fa fa-hand-holding-dollar"></i>
                    </div>
                    <div class="ml-2">
                        <p class="mb-2 text-sm font-bold text-gray-600 dark:text-gray-400">Total Sale Amount</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{
                            formatNumber(totalSaleAmount) }}
                            MMK</p>
                    </div>
                </div>
                <!-- End Card -->
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div
                        class="px-3 py-2 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                        <i class="fa fa-chart-line "></i>
                    </div>
                    <div class="ml-2">
                        <p class="mb-2 text-sm font-bold text-gray-600 dark:text-gray-400">Total Sale Numbers</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ totalSaleNumber }}</p>
                    </div>
                </div>
                <!-- End Card -->
                <!-- Card -->
                <div
                    class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 col-span-2 md:col-span-1">
                    <div
                        class="px-4 py-2 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                        <i class="fa fa-dollar-sign "></i>
                    </div>
                    <div class="ml-2">
                        <div class="grid grid-cols-3">
                            <div class="col-span-2">
                                <p class="mb-2 text-sm font-bold text-gray-600 dark:text-gray-400">Price Per No.</p>
                            </div>
                            <div class="col-span-1">
                                <div class="w-full text-sm text-yellow-500 text-right">
                                    <button class="btn" @click="openPriceModal"><i class="fa fa-pen"></i></button>
                                </div>
                            </div>
                        </div>

                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{
                            formatNumber(amount)
                        }}</p>
                    </div>
                </div>
                <!-- End Card -->

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4  shadow-xl sm:rounded-lg md:col-span-3">

                    <div class="flex justify-between w-full">
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Purchase Numbers</h2>
                        </div>
                        <div>
                            <button @click="newPurchaseModal"
                                class="bg-indigo-600 text-white px-4 py-2 rounded w-full whitespace-nowrap text-sm">
                                Add <i class="fa fa-plus-circle ml-2"></i>
                            </button>
                        </div>


                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 my-4">
                        <div class="col-span-1">
                            <!-- <label for="general" class="text-sm">Enter No./ Name/ etc.</label> -->
                            <div> <span
                                    class="leading-snug font-normal text-center text-gray-400 absolute bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-2">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" v-model="searchForm.general" name="general" id="general"
                                    class="pl-10 focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200"
                                    placeholder="Number, Name, etc." v-on:keypress.enter="searchData" />
                            </div>

                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <!-- <label for="date-range" class="text-sm">Select Date Range</label> -->
                            <VueDatePicker v-model="searchForm.date" :range="{ partialRange: true }"
                                :enable-time-picker="false" model-type="yyyy-MM-dd" id="date-range"
                                class="text-gray-400 text-sm focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none" />
                        </div>

                        <div class="col-span-1 md:col-span-2 flex justify-between w-full content-end ">


                            <button @click="searchData"
                                class="bg-indigo-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap">
                                Search <i class="fa fa-search ml-1"></i>
                            </button>
                            <button @click="downloadExcel"
                                class="bg-green-800 text-white px-4 py-2 rounded text-sm ml-2 whitespace-nowrap">
                                Sold Out <i class="fa fa-file-excel ml-1"></i>
                            </button>
                            <button @click="downloadRemainingExcel"
                                class="bg-green-800 text-white px-4 py-2 rounded text-sm ml-2 whitespace-nowrap">
                                Remaining <i class="fa fa-file-excel ml-1"></i>
                            </button>


                        </div>
                    </div>
                    <div class="hidden md:block">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Number</th>
                                    <th scope="col" class="px-6 py-3">Amount</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Phone</th>
                                    <th scope="col" class="px-6 py-3">Purchase Date</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="purchase in purchaseNumbers.data" :key="purchase.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                        purchase.number }}</td>
                                    <td class="px-6 py-4">{{ purchase.amount }}</td>
                                    <td class="px-6 py-4">{{ purchase.customer_name }}</td>
                                    <td class="px-6 py-4">{{ purchase.customer_phone }}</td>
                                    <td class="px-6 py-4">{{ purchase.purchase_date }}</td>
                                    <td class="px-6 py-4">
                                        <button @click="openEditPurchaseModal(purchase)"
                                            class="text-blue-500">Edit</button>
                                        <button @click="deletePurchase(purchase.id)"
                                            class="text-red-500 ml-4">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="md:hidden divide-y divide-solid">
                        <div v-for="purchase in purchaseNumbers.data" :key="purchase.id" class="grid grid-cols-4 py-5">
                            <div>
                                <span class="text-sm text-gray-500">{{ purchase.purchase_date }}</span>
                            </div>
                            <div>
                                <span
                                    class="bg-blue-100 text-blue-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{
                                        purchase.number }} </span> <br />
                                <span class="text-sm text-gray-500  w-full">{{ purchase.amount }} MMK</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 w-full">{{ purchase.customer_name }}</span> <br />
                                <span class="text-sm text-gray-500  w-full">{{ purchase.customer_phone }}</span>
                            </div>
                            <div class="text-sm text-gray-400 w-full grid justify-end">
                                <div>
                                    <button @click="openEditPurchaseModal(purchase)" class="text-yellow-500 text-sm"><i
                                            class="fa fa-pen"></i></button> |
                                    <button @click="deletePurchase(purchase.id)" class="text-red-600 text-sm"><i
                                            class="fa fa-trash"></i></button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <span v-if="purchaseNumbers.total" class="w-full block mt-4">
                        <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                            <span class="font-semibold text-gray-900 dark:text-white">{{ purchaseNumbers.from }} -
                                {{ purchaseNumbers.to }}</span> of
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ purchaseNumbers.total }}</span>
                        </span>

                    </span>
                    <span v-if="purchaseNumbers.links">
                        <pagination class="mt-4" :links="purchaseNumbers.links" />
                    </span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 overflow-hidden shadow-xl sm:rounded-lg col-span-1">

                    <!-- Winning Numbers Table -->
                    <div class="flex justify-between w-full">
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Winning Numbers</h2>
                        </div>
                        <div>
                            <button @click="newWinningModal"
                                class="bg-yellow-600 text-white px-4 py-2 rounded w-full whitespace-nowrap text-sm">
                                Add <i class="fa fa-plus-circle ml-2"></i>
                            </button>
                        </div>


                    </div>
                    <div class="w-full my-4">

                        <span
                            class="leading-snug font-normal text-center text-gray-400 absolute bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-2">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" v-model="searchForm.winning" name="general" id="general"
                            class="pl-10 focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200"
                            placeholder="Enter Number" v-on:keypress.enter="searchData" />
                    </div>
                    <div class="divide-y divide-solid">
                        <div v-for="winning in winningNumbers.data" :key="winning.id" class="grid grid-cols-3 py-5">
                            <div class="w-full"><span class="text-sm text-gray-500">{{ winning.draw_date }}</span>
                            </div>
                            <div class="w-full"><span
                                    class="bg-blue-100 text-blue-800 text-sm font-bold me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{
                                        winning.number }}</span></div>

                            <div class="text-sm text-gray-400 w-full grid justify-end">
                                <div>
                                    <button @click="openEditWinningModal(winning)" class="text-yellow-500 text-sm"><i
                                            class="fa fa-pen"></i></button> |
                                    <button @click="deleteWinning(winning.id)" class="text-red-600 text-sm"><i
                                            class="fa fa-trash"></i></button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <span v-if="winningNumbers.total" class="w-full block">
                        <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing
                            <span class="font-semibold text-gray-900 dark:text-white">{{ winningNumbers.from }}
                                -
                                {{ winningNumbers.to }}</span> of
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ winningNumbers.total }}</span>
                        </span>

                    </span>
                    <span v-if="winningNumbers.links">
                        <pagination class="mt-4" :links="winningNumbers.links" />
                    </span>


                </div>
            </div>




            <!--  Purchase Modal -->
            <div v-if="showPurchaseModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded w-full md:w-1/3 ">
                    <h2 class="text-lg font-bold mb-4" v-if="!purchaseEditMode">Add Purchase Info</h2>
                    <h2 class="text-lg font-bold mb-4" v-else>Edit Purchase Info</h2>
                    <form @submit.prevent="submitPurchase" class="grid gap-y-2">
                        <input v-model="purchaseForm.customer_name" type="text" placeholder="Customer Name"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.customer_name" class="text-red-600 text-sm ">{{
                            purchaseForm.errors.customer_name }}
                        </div>
                        <input v-model="purchaseForm.customer_phone" type="text" placeholder="Phone Number"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.customer_phone" class="text-red-600 text-sm">{{
                            purchaseForm.errors.customer_phone }}
                        </div>
                        <input v-model="purchaseForm.customer_viber" type="text" placeholder="Viber"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.customer_viber" class="text-red-600 text-sm ">{{
                            purchaseForm.errors.customer_viber }}
                        </div>
                        <input v-model="purchaseForm.customer_facebook" type="text" placeholder="Facebook Account"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.customer_facebook" class="text-red-600 text-sm">{{
                            purchaseForm.errors.customer_facebook }}
                        </div>
                        <textarea v-model="purchaseForm.number" placeholder="Number"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.number" class="text-red-600 text-sm ">{{
                            purchaseForm.errors.number }}
                        </div>
                        <input v-model="purchaseForm.amount" type="text" placeholder="Cost Per Lucky Number"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.amount" class="text-red-600 text-sm">{{
                            purchaseForm.errors.amount }}
                        </div>
                        <input v-model="purchaseForm.purchase_date" type="date"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="purchaseForm.errors.purchase_date" class="text-red-600 text-sm">{{
                            purchaseForm.errors.purchase_date }}
                        </div>
                        <div class="flex justify-end gap-2">
                            <button @click="closePurchaseModal()" type="button"
                                class="px-4 py-2 border rounded">Cancel</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Winning Modal -->
            <div v-if="showWinningModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded w-full md:w-1/3">
                    <h2 class="text-lg font-bold mb-4" v-if="!winningEditMode">Add Winning Number</h2>
                    <h2 class="text-lg font-bold mb-4" v-else>Edit Winning Number</h2>
                    <form @submit.prevent="submitWinning" class="grid gap-y-2">
                        <input v-model="winningForm.number" type="text" placeholder="Number"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="winningForm.errors.number" class="text-red-600 text-sm ">{{
                            winningForm.errors.number }}
                        </div>
                        <input v-model="winningForm.draw_date" type="date"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="winningForm.errors.draw_date" class="text-red-600 text-sm ">{{
                            winningForm.errors.draw_date }}
                        </div>
                        <div class="flex justify-end gap-2">
                            <button @click="closeWinningModal" type="button"
                                class="px-4 py-2 border rounded">Cancel</button>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Price Modal -->
            <div v-if="showPriceModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded w-full md:w-1/3">

                    <h2 class="text-lg font-bold mb-4">Edit Config</h2>
                    <form @submit.prevent="submitPrice" class="grid gap-y-2">
                        <input v-model="priceForm.amount" type="text" placeholder="Amount"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="priceForm.errors.amount" class="text-red-600 text-sm ">{{
                            priceForm.errors.amount }}
                        </div>
                        <input v-model="priceForm.title" type="text" placeholder="Title"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200" />
                        <div v-if="priceForm.errors.title" class="text-red-600 text-sm ">{{
                            priceForm.errors.title }}
                        </div>
                        <textarea v-model="priceForm.display" type="text" placeholder="Display Text"
                            class="focus:ring focus:ring-indigo-500 focus:border-indigo-500 focus:ring-opacity-10 focus:outline-none flex-1 block w-full rounded-md sm:text-sm border-gray-200"
                            rows="10"></textarea>
                        <div v-if="priceForm.errors.display" class="text-red-600 text-sm ">{{
                            priceForm.errors.display }}
                        </div>
                        <div class="flex justify-end gap-2">
                            <button @click="closePriceModal" type="button"
                                class="px-4 py-2 border rounded">Cancel</button>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>




        </div>
    </AppLayout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { onMounted, ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import ToggleSwitch from '@/Components/ToggleSwitch.vue'
import '@vuepic/vue-datepicker/dist/main.css'
export default {
    name: 'Dashboard',
    components: {
        AppLayout,
        VueDatePicker,
        ToggleSwitch,
        Pagination
    },
    props: {
        winningNumbers: Array,
        purchaseNumbers: Array,
        todaySaleAmount: Number,
        todaySaleNumber: Number,
        totalSaleAmount: Number,
        totalSaleNumber: Number,
        amount: Number,
        title: String,
        display: String,
    },
    setup(props) {
        const showPurchaseModal = ref(false);
        const purchaseEditMode = ref(false);
        const winningEditMode = ref(false);
        const showWinningModal = ref(false);
        const showPriceModal = ref(false);
        const purchaseForm = useForm({
            number: "",
            purchase_date: "",
            amount: props.amount,
            customer_name: "",
            customer_phone: "",
            customer_facebook: "",
            customer_viber: "",
        });

        const winningForm = useForm({
            number: "",
            draw_date: "",
        });
        const priceForm = useForm({
            amount: props.amount,
            title: props.title,
            display: props.display,
        });

        const searchForm = useForm({
            general: "",
            winning: "",
            date: "",
        })
        const searchData = () => {
            searchForm.post(`/dashboard`, {
                onSuccess: () => {

                },

            });
        }
        const newPurchaseModal = () => {
            showPurchaseModal.value = true;
            purchaseEditMode.value = false;
        }
        const closePurchaseModal = () => {
            showPurchaseModal.value = false;
            purchaseEditMode.value = false;
            purchaseForm.reset();
            purchaseForm.clearErrors();
        }

        const submitPurchase = () => {
            if (purchaseEditMode.value) {
                purchaseForm.post(`/edit-purchase/${purchaseForm.id}`, {
                    onSuccess: (page) => {
                        Toast.fire({
                            icon: "success",
                            title: page.props.flash.message,
                        });
                        purchaseEditMode.value = false;
                        closePurchaseModal();
                    },

                });
            } else {
                purchaseForm.post("/add-purchase", {
                    onSuccess: (page) => {
                        Toast.fire({
                            icon: "success",
                            title: page.props.flash.message,
                        });
                        closePurchaseModal();
                    }

                });
            }

        };

        const submitWinning = () => {
            if (winningEditMode.value) {
                winningForm.post(`/edit-winning/${winningForm.id}`, {
                    onSuccess: (page) => {
                        Toast.fire({
                            icon: "success",
                            title: page.props.flash.message,
                        });
                        closeWinningModal();
                    },
                });
            } else {
                winningForm.post("/add-winning", {
                    onSuccess: (page) => {
                        Toast.fire({
                            icon: "success",
                            title: page.props.flash.message,
                        });
                        closeWinningModal();
                    },
                });
            }

        };
        const openEditPurchaseModal = (purchase) => {
            purchaseForm.id = purchase.id;
            purchaseForm.customer_name = purchase.customer_name;
            purchaseForm.customer_facebook = purchase.customer_facebook;
            purchaseForm.customer_phone = purchase.customer_phone;
            purchaseForm.customer_viber = purchase.customer_viber;
            purchaseForm.amount = purchase.amount;
            purchaseForm.number = purchase.number;
            purchaseForm.purchase_date = purchase.purchase_date;
            purchaseEditMode.value = true;
            showPurchaseModal.value = true;
        };



        const deletePurchase = (id) => {
            Toast.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data !",
                icon: "warning",
                timer: false,
                position: "center",
                showCancelButton: true,
                dangerMode: true,
            }).then((x) => {
                if (x.isConfirmed) {
                    router.delete(`/delete-purchase/${id}`, {
                        onSuccess: (page) => {
                            Toast.fire({
                                icon: "success",
                                title: page.props.flash.message,
                            });
                        },
                    });

                } else {
                    Toast.fire("Your data is safe!");
                }
            });

        };
        const newWinningModal = () => {
            showWinningModal.value = true;
            winningEditMode.value = false;
        }
        const closeWinningModal = () => {
            showWinningModal.value = false;
            winningEditMode.value = false;
            winningForm.reset();
            winningForm.clearErrors();
        }
        const openEditWinningModal = (winning) => {
            winningForm.id = winning.id;
            winningForm.number = winning.number;
            winningForm.draw_date = winning.draw_date;
            showWinningModal.value = true;
            winningEditMode.value = true;
        };


        const deleteWinning = (id) => {
            Toast.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data !",
                icon: "warning",
                timer: false,
                position: "center",
                showCancelButton: true,
                dangerMode: true,
            }).then((x) => {
                if (x.isConfirmed) {
                    router.delete(`/delete-winning/${id}`, {
                        onSuccess: (page) => {
                            Toast.fire({
                                icon: "success",
                                title: page.props.flash.message,
                            });
                        },
                    });

                } else {
                    Toast.fire("Your data is safe!");
                }
            });

        };

        const openPriceModal = () => {
            showPriceModal.value = true;
        }
        const closePriceModal = () => {
            showPriceModal.value = false;
            priceForm.reset();
            priceForm.clearErrors();
        }

        const submitPrice = () => {
            priceForm.post("/update-price", {
                onSuccess: (page) => {
                    Toast.fire({
                        icon: "success",
                        title: page.props.flash.message,
                    });
                    closePriceModal();
                }

            });
        };
        const downloadExcel = () => {
            axios.post("/exportExcel", searchForm, { responseType: "blob" }).then((response) => {
                console.log(response);
                var a = document.createElement("a");
                document.body.appendChild(a);
                a.style = "display: none";
                var blob = new Blob([response.data], {
                    type: response.headers["content-type"],
                });
                const link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = `sold_${new Date().getTime()}.xlsx`;
                link.click();
            });
        }
        const downloadRemainingExcel = () => {
            axios.post("/exportRemainingExcel", {}, { responseType: "blob" }).then((response) => {
                console.log(response);
                var a = document.createElement("a");
                document.body.appendChild(a);
                a.style = "display: none";
                var blob = new Blob([response.data], {
                    type: response.headers["content-type"],
                });
                const link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = `remaining_${new Date().getTime()}.xlsx`;
                link.click();
            });
        }
        const formatNumber = (number) => {
            if (number === null || number === undefined) {
                return "0";
            }
            return Number(number).toLocaleString("en-US");
        }
        return {

            showPurchaseModal,
            newPurchaseModal,
            purchaseEditMode,
            closePurchaseModal,

            showWinningModal,
            newWinningModal,
            closeWinningModal,

            showPriceModal,
            openPriceModal,
            closePriceModal,

            purchaseForm,
            winningForm,
            priceForm,
            submitPurchase,
            submitWinning,
            submitPrice,
            openEditPurchaseModal,
            deletePurchase,
            openEditWinningModal,
            deleteWinning,
            searchData,
            searchForm,
            formatNumber,
            downloadExcel,
            downloadRemainingExcel
        };
    },
};
</script>