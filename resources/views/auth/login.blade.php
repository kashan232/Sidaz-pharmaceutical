<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sidaz Pharmaceutical - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Using Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7', /* Corporate Blue */
                            700: '#0369a1',
                            900: '#0c4a6e',
                        },
                        medical: {
                            500: '#10b981', /* Emerald/Medical Green */
                            600: '#059669',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f8fafc;
        }
        /* Custom Left Panel Background */
        .auth-sidebar {
            background: linear-gradient(145deg, #0c4a6e 0%, #0369a1 100%);
            position: relative;
        }
        .auth-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.05) 0%, transparent 50%),
                              radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
            z-index: 1;
        }
        .logo-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 14px;
            box-shadow: 0 4px 14px 0 rgba(2, 132, 199, 0.39);
        }
    </style>
</head>
<body class="antialiased font-sans text-gray-800">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Left Sidebar (Branding & Logo) -->
        <div class="auth-sidebar hidden md:flex md:w-5/12 lg:w-1/2 flex-col justify-center items-center text-white px-8 md:px-16 overflow-hidden">
            <div class="relative z-10 w-full max-w-md">
                
                <!-- Premium Logo Design -->
                <div class="flex items-center gap-4 mb-8">
                    <!-- Custom/Dynamic Logo Mark -->
                    @if(\App\Models\Setting::get('company_logo'))
                        <div class="flex-shrink-0 relative">
                            <img src="{{ \App\Models\Setting::get('company_logo') }}" alt="{{ \App\Models\Setting::get('company_name', 'Sidaz') }} Logo" class="w-16 h-16 object-contain bg-white rounded-xl shadow-sm p-1">
                        </div>
                    @else
                        <!-- Stylized Medical Cross / Leaf Logo Mark (Fallback) -->
                        <div class="logo-mark flex-shrink-0 relative">
                            <svg class="w-8 h-8 text-white absolute" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            <div class="absolute inset-0 bg-white/20 rounded-xl transform rotate-45 scale-75"></div>
                        </div>
                    @endif
                    
                    <!-- Text Logo -->
                    <div class="flex flex-col">
                        @php 
                            $companyName = \App\Models\Setting::get('company_name', 'Sidaz');
                            $parts = explode(' ', $companyName, 2);
                            $firstWord = $parts[0] ?? 'Sidaz';
                            $remaining = $parts[1] ?? 'Pharmaceutical';
                        @endphp
                        <span class="text-4xl font-bold tracking-tight text-white leading-none mb-1">{{ $firstWord }}</span>
                        <span class="text-sm font-medium tracking-[0.2em] text-brand-100 uppercase">{{ $remaining }}</span>
                    </div>
                </div>

                <div class="mt-12 space-y-6 text-brand-50">
                    <h2 class="text-3xl font-semibold leading-tight">Pharma Manufacturing ERP</h2>
                    <div class="space-y-4">
                        <p class="text-base text-brand-100 leading-relaxed opacity-95">
                            {{ \App\Models\Setting::get('login_description', 'A complete enterprise solution designed for pharmaceutical manufacturing and inventory management.') }}
                        </p>
                        <!-- Only show bullets if the default description is used, otherwise hide to keep layout clean for custom text -->
                        @if(\App\Models\Setting::get('login_description') === null || \App\Models\Setting::get('login_description') === 'A complete enterprise solution designed for pharmaceutical manufacturing and inventory management.')
                            <ul class="text-sm text-brand-100/80 space-y-2 list-disc list-inside">
                                <li>Manage Purchases with Bilty & Transport Details</li>
                                <li>Raw Material (RM) & Packaging Departments</li>
                                <li>Advanced Units (i.U, KG, Ltr, PCS)</li>
                                <li>Product Formulas & Composition Tracking</li>
                            </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Side (Login Form) -->
        <div class="w-full md:w-7/12 lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-24 bg-white relative">
            
            <div class="w-full max-w-md mx-auto">
                
                <!-- Mobile Logo (Hidden on desktop) -->
                <div class="md:hidden flex items-center gap-3 mb-10">
                    @if(\App\Models\Setting::get('company_logo'))
                        <div class="flex-shrink-0 w-12 h-12">
                            <img src="{{ \App\Models\Setting::get('company_logo') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="logo-mark flex-shrink-0 w-12 h-12">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-gray-900 leading-none mb-1">{{ $firstWord }}</span>
                        <span class="text-xs font-semibold tracking-widest text-brand-600 uppercase">{{ $remaining }}</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">Welcome back</h3>
                    <p class="text-sm text-gray-500 mt-2">Please enter your credentials to log in.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500 flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-sm text-red-700 font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-200 sm:text-sm"
                                placeholder="name@company.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-200 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4.5 w-4.5 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2.5 block text-sm font-medium text-gray-600 cursor-pointer">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-200">
                            Sign In
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
                
                <!-- Footer & Branding -->
                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-400">
                        Powered by <span class="font-bold text-gray-700 tracking-wide">Prowave Technologies</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
