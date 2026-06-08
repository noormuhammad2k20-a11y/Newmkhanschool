<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduGov Management - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-lowest": "#ffffff",
                        "surface-bright": "#f8f9fa",
                        "on-tertiary-container": "#999897",
                        "surface-dim": "#d9dadb",
                        "on-tertiary-fixed-variant": "#474747",
                        "on-surface-variant": "#454652",
                        "surface-tint": "#4c56af",
                        "secondary-fixed": "#d6e5ef",
                        "on-tertiary-fixed": "#1b1c1c",
                        "primary": "#000666",
                        "on-primary-fixed-variant": "#343d96",
                        "on-secondary-container": "#56656e",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#e1e3e4",
                        "inverse-primary": "#bdc2ff",
                        "primary-container": "#1a237e",
                        "error-container": "#ffdad6",
                        "outline-variant": "#c6c5d4",
                        "outline": "#767683",
                        "surface-container-high": "#e7e8e9",
                        "tertiary-fixed-dim": "#c8c6c6",
                        "surface-container": "#edeeef",
                        "secondary-container": "#d3e2ed",
                        "primary-fixed": "#e0e0ff",
                        "primary-fixed-dim": "#bdc2ff",
                        "surface": "#f8f9fa",
                        "on-primary-fixed": "#000767",
                        "on-primary-container": "#8690ee",
                        "secondary": "#526069",
                        "on-background": "#191c1d",
                        "inverse-on-surface": "#f0f1f2",
                        "secondary-fixed-dim": "#bac9d3",
                        "on-secondary-fixed": "#0f1d25",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "tertiary": "#1b1b1b",
                        "on-error": "#ffffff",
                        "on-primary": "#ffffff",
                        "surface-container-low": "#f3f4f5",
                        "tertiary-container": "#303030",
                        "inverse-surface": "#2e3132",
                        "tertiary-fixed": "#e4e2e1",
                        "on-surface": "#191c1d",
                        "on-secondary-fixed-variant": "#3b4951",
                        "surface-variant": "#e1e3e4",
                        "on-error-container": "#93000a",
                        "background": "#f8f9fa"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "xl": "32px",
                        "xs": "4px",
                        "md": "16px",
                        "margin-mobile": "16px",
                        "sm": "8px",
                        "max-width": "1440px",
                        "base": "4px",
                        "lg": "24px",
                        "margin-desktop": "32px",
                        "gutter": "20px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-xl": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-xl": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex items-center justify-center p-md">
    
    <div class="w-full max-w-md bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-lg">
        
        <div class="flex flex-col items-center justify-center mb-lg">
            <div class="w-16 h-16 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-headline-xl font-bold mb-md">
                SE
            </div>
            <h1 class="font-headline-lg text-headline-lg text-primary text-center">State Education</h1>
            <p class="font-body-md text-secondary mt-xs">Login to your account</p>
        </div>

        @if ($errors->any())
            <div class="bg-error-container text-on-error-container p-sm rounded-lg mb-md font-body-md">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-md">
            @csrf
            
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface-variant uppercase" for="email">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">mail</span>
                    <input class="w-full border border-outline-variant rounded-lg py-sm pl-10 pr-sm font-body-md bg-transparent focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                </div>
            </div>

            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface-variant uppercase" for="password">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">lock</span>
                    <input class="w-full border border-outline-variant rounded-lg py-sm pl-10 pr-sm font-body-md bg-transparent focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" type="password" name="password" id="password" required placeholder="Enter your password">
                </div>
            </div>

            <button type="submit" class="mt-sm w-full bg-primary hover:bg-on-primary-fixed-variant text-on-primary font-label-md text-label-md py-sm px-md rounded-full transition-colors flex items-center justify-center gap-xs h-10 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[20px]">login</span>
                Sign In
            </button>
        </form>

        <div class="mt-lg pt-md border-t border-outline-variant">
            <p class="font-label-md text-secondary uppercase mb-sm">Demo Accounts (Password: password)</p>
            <div class="flex flex-col gap-xs font-body-md text-on-surface-variant text-sm">
                <div class="flex justify-between items-center bg-surface-container py-xs px-sm rounded">
                    <span>Admin:</span>
                    <span class="font-medium text-primary">admin@school.com</span>
                </div>
                <div class="flex justify-between items-center bg-surface-container py-xs px-sm rounded">
                    <span>Teacher:</span>
                    <span class="font-medium text-primary">teacher@school.com</span>
                </div>
                <div class="flex justify-between items-center bg-surface-container py-xs px-sm rounded">
                    <span>Student:</span>
                    <span class="font-medium text-primary">student@school.com</span>
                </div>
            </div>
        </div>
        
    </div>

</body>
</html>
