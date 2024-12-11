<template>
    <Layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Select Your Lucky Numbers
            </h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white w-full dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg py-4 px-4">
                    <h2 class="text-lg font-semibold mb-2">Selected Numbers:</h2>
                    <div v-if="selectedNumbers.length" class="grid grid-cols-5 md:grid-cols-10 gap-4">
                        <div v-for="number in selectedNumbers" :key="number.number"
                            class="relative w-12 h-12 flex items-center justify-center rounded-full shadow-lg font-semibold bg-blue-200">
                            {{ number.number }}
                            <button @click="removeFromCart(number.number)"
                                class="absolute top-0 right-0 text-red-500 w-5 h-5 bg-white border border-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition duration-200"
                                aria-label="Remove from selected numbers">
                                ×
                            </button>
                            <!-- Remaining Time -->
                            <small class="absolute bottom-0 translate-y-6 text-xs text-gray-600">{{
                                number.remainingTime }}s</small>
                        </div>

                    </div>
                    <p v-else class="text-gray-500">No numbers selected yet.</p>

                    <div class="mt-8 flex justify-between" v-if="selectedNumbers.length">
                        <div class="flex whitespace-nowrap font-semibold text-sm items-center">
                            <h4>Total Cost : </h4> {{ selectedNumbers.length * amount }} MMK
                        </div>
                        <div class="flex justify-end">
                            <button @click="checkout"
                                class="bg-green-500 text-white px-6 py-2 rounded shadow hover:bg-green-600">
                                Check Out <i class="fa fa-cart-shopping text-green-800"></i>
                            </button>
                            <button @click="clearAll"
                                class="ml-2 px-4 py-2 bg-gray-300 text-black rounded shadow hover:bg-gray-400">
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4 mt-4">
                    <!-- Search Filter -->
                    <div class="mb-4 flex justify-center">
                        <input v-model="searchQuery" type="text" placeholder="Search number..."
                            class="border border-gray-300 rounded px-4 py-2 w-1/2" />
                    </div>

                    <!-- Number Grid -->
                    <div class="grid grid-cols-5 md:grid-cols-10 gap-4 p-4">
                        <div v-for="number in filteredNumbers" :key="number.number" class="place-self-auto">
                            <button @click="addToCart(number.number)" :disabled="number.disabled" :class="[
                                'w-12 h-12 flex items-center justify-center rounded-full shadow-lg font-semibold',
                                selectedNumbers.includes(number.number)
                                    ? 'bg-blue-500 text-white'
                                    : 'bg-gray-100 hover:bg-blue-200',
                                number.disabled ? 'bg-red-800 text-white cursor-not-allowed hover:bg-gray-600' : '',
                                number.is_cart ? 'bg-gray-600 text-white cursor-not-allowed hover:bg-gray-600' : ''
                            ]">
                                {{ number.number }}

                            </button>
                            <small class="text-xs text-gray-600">
                                <label v-if="number.disabled">Sold Out!</label>
                                <label v-if="number.is_cart">Reserved!</label>
                            </small>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { onMounted, ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

import Layout from '@/Layouts/Layout.vue';
export default {
    name: 'ListNumber',
    components: {
        Layout,
        Pagination
    },
    props: {
        numbers: {
            type: Array,
            required: true,
        },
        amount: Number,
        display: String
    },
    setup(props) {
        const selectedNumbers = ref([]);
        const searchQuery = ref("");
        // Fetch cart items from the backend
        const fetchCartItems = async () => {
            try {
                const response = await axios.get("/cart/items");
                selectedNumbers.value = response.data;
            } catch (error) {
                console.error("Failed to load cart items:", error);
                throw error; // Ensure the error propagates
            }
        };
        // Start countdown
        const startCountdown = async () => {
            while (true) {
                // Wait for 1 second
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Update the remaining time and filter expired numbers
                selectedNumbers.value = selectedNumbers.value.filter((item) => {
                    if (item.remainingTime > 0) {
                        item.remainingTime--;
                        return true;
                    } else {
                        // Notify the user and sync with the backend
                        axios.post("/cart/clear-item", { number: item.number })
                            .then(() => {
                                Toast.fire({
                                    icon: "info",
                                    title: `Number ${item.number} has expired and was removed.`,
                                });
                            })
                            .catch((error) => {
                                console.error("Error removing expired item:", error);
                            });
                        return false;
                    }
                });
            }
        };
        // Filtered numbers based on the search query
        const filteredNumbers = computed(() =>
            props.numbers.filter((number) =>
                number.number.includes(searchQuery.value)
            )
        );

        const addToCart = (number) => {
            if (!selectedNumbers.value.includes(number)) {
                // Add to cart (API call)
                router.post("/cart/add", { number }, {
                    onSuccess: (page) => {
                        //     
                        fetchCartItems();
                        selectedNumbers.value.push(number);
                        Toast.fire({
                            icon: "success",
                            title: page.props.flash.message,
                        });
                    },
                    onError: (errors) => {
                        console.log("error ..");
                    },
                });
            }
        };

        const removeFromCart = (number) => {
            router.post("/cart/clear-item", { number }, {
                onSuccess: (page) => {
                    Toast.fire({
                        icon: "success",
                        title: "နံပါတ် '" + number + "' ကို ဖျက်လိုက်ပါပြီ။",
                    });
                },
                onError: (errors) => {
                    console.log("error ..");
                },
            });
            fetchCartItems();
        };

        const clearAll = () => {
            Toast.fire({
                title: "ရွေးချယ်ထားသော နံပါတ်များအားလုံး ဖျက်ရန် သေချာပါသလား။",
                text: "ဖျက်လိုက်သော နံပါတ်များ ထပ်မံရွေးချယ်နိုင်ပါသည်။",
                icon: "warning",
                timer: false,
                position: "center",
                showCancelButton: true,
                dangerMode: true,
            }).then((x) => {
                if (x.isConfirmed) {
                    router.post("/cart/clear", {}, {
                        onSuccess: (page) => {
                            selectedNumbers.value = [];
                            Toast.fire({
                                icon: "success",
                                title: page.props.flash.message,
                            });
                        },
                        onError: (errors) => {
                            console.log("error ..");
                        },
                    });

                } else {
                    Toast.fire("Your data is safe!");
                }
            });

        };

        const checkout = () => {
            Toast.fire({
                title: "ငွေပေးခြေရန်",
                html: props.display,
                icon: "warning",
                position: "center",
                timer: false,
                toast: false
            });
            // router.post("/cart/checkout", {}, {
            //     onSuccess: (page) => {
            //         selectedNumbers.value = [];
            //         Toast.fire({
            //             icon: "success",
            //             title: page.props.flash.message,
            //         });
            //     },
            //     onError: (errors) => {
            //         console.log("error ..");
            //     },
            // });
        };

        onMounted(() => {
            fetchCartItems().then(() => {
                console.log('Done fetching cart items.');
                startCountdown();
            }).catch((error) => {
                console.error('Error during initial fetch:', error);
            });

        });

        return {
            selectedNumbers,
            searchQuery,
            filteredNumbers,
            addToCart,
            removeFromCart,
            clearAll,
            checkout,
        };
    },
};
</script>