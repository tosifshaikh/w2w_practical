<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Orca Tech - Developer Assignment" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <header class="bg-white shadow-sm dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Orca Tech</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Developer Assignment</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium dark:text-gray-300 dark:hover:text-white"
                            >
                                Login
                            </Link>
                            <Link
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-8">
                    <!-- Introduction -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-4">
                            Welcome to Orca Tech Developer Assignment
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                            This assignment evaluates your Laravel development skills through a practical CSV upload and data management task. 
                            You will build a transaction management system that processes CSV files and displays the data.
                        </p>
                    </div>

                    <!-- Assignment Overview -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Assignment Overview</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Build a Laravel application that can upload and process a CSV file containing transaction data, 
                            then display this data in a clean, organized table with basic filtering capabilities.
                        </p>
                    </div>

                    <!-- Task Requirements -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Task Requirements</h3>
                        
                        <div class="space-y-6">
                            <!-- Task 1 -->
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">1. Database Design</h4>
                                <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Create a <code class="bg-gray-100 px-2 py-1 rounded text-sm dark:bg-gray-700">Transaction</code> model and migration</li>
                                    <li>• Include fields: customer_name, email, last4, currency, amount, type, status, transaction_date</li>
                                </ul>
                            </div>

                            <!-- Task 2 -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">2. CSV Upload Functionality</h4>
                                <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Create upload form with file validation</li>
                                    <li>• Process CSV data and store in database</li>
                                    <li>• Display success/error messages</li>
                                </ul>
                            </div>

                            <!-- Task 3 -->
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">3. Data Display</h4>
                                <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Create transactions listing page with pagination</li>
                                    <li>• Add search by customer name or email</li>
                                    <li>• Add filters for transaction type and status</li>
                                </ul>
                            </div>

                            <!-- Task 4 -->
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">4. Code Quality & Testing (Nice to Have)</h4>
                                <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Use Form Request validation</li>
                                    <li>• Write basic feature tests</li>
                                    <li>• Follow Laravel coding standards</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sample CSV Data -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Sample CSV Format</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Your application should process CSV files with the following format:</p>
                        
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-800 dark:text-gray-200"><code>customer_name,email,last4,currency,amount,type,status,transaction_date
John Smith,john@example.com,1234,USD,150.50,payment,completed,2024-01-15 14:30:00
Jane Doe,jane@example.com,5678,EUR,89.99,payment,pending,2024-01-16 09:15:00
Mike Johnson,mike@example.com,9012,USD,200.00,refund,completed,2024-01-16 11:45:00</code></pre>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Field Descriptions:</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li><strong>customer_name:</strong> Full name of the customer</li>
                                <li><strong>email:</strong> Customer's email address</li>
                                <li><strong>last4:</strong> Last 4 digits of payment method</li>
                                <li><strong>currency:</strong> Currency code (USD, EUR, GBP, etc.)</li>
                                <li><strong>amount:</strong> Transaction amount (decimal)</li>
                                <li><strong>type:</strong> payment, refund, or chargeback</li>
                                <li><strong>status:</strong> completed, pending, or failed</li>
                                <li><strong>transaction_date:</strong> Date and time of transaction (YYYY-MM-DD HH:MM:SS)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Technical Requirements -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Technical Requirements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Backend Requirements</h4>
                                <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Laravel 12 framework</li>
                                    <li>• Eloquent ORM for data handling</li>
                                    <li>• Form Request validation</li>
                                    <li>• Resource Controllers</li>
                                    <li>• Proper error handling</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Frontend Requirements</h4>
                                <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                    <li>• Vue.js 3 with Inertia.js OR API approach</li>
                                    <li>• Responsive design</li>
                                    <li>• Clean, professional UI</li>
                                    <li>• Form validation feedback</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Criteria -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Evaluation Criteria</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Code Quality & Structure</span>
                                    <span class="text-gray-900 dark:text-white font-medium">30%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Functionality & Features</span>
                                    <span class="text-gray-900 dark:text-white font-medium">25%</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Database Design</span>
                                    <span class="text-gray-900 dark:text-white font-medium">20%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Testing & Error Handling</span>
                                    <span class="text-gray-900 dark:text-white font-medium">25%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Get Started -->
                    <div class="border-t pt-8">
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Expected completion time: <strong>3-5 hours</strong>
                            </p>
                            <div class="space-x-4">
                                <Link
                                    :href="route('register')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-medium inline-block"
                                >
                                    Start Assignment
                                </Link>
                                <a
                                    href="/sample-transactions.csv"
                                    download
                                    class="border border-gray-300 hover:border-gray-400 text-gray-700 dark:text-gray-300 dark:border-gray-600 px-6 py-3 rounded font-medium inline-block"
                                >
                                    Download Sample CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>© 2024 Orca Tech. Developer Assignment Challenge.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
code {
    font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
}
</style>