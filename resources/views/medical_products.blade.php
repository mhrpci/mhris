<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase - Brands</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 2rem;
            padding-top: 1rem;
            position: relative;
            min-height: 100vh;
        }

        /* Add overlay background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.1;
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #2c5282;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .back-button:hover {
            background-color: #e2e8f0;
            color: #1a365d;
        }

        .category-section {
            margin-bottom: 4rem;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #2c5282;
        }

        .category-logo {
            width: 50px;
            height: 50px;
            padding: 10px;
            background: #2c5282;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-logo svg {
            width: 100%;
            height: 100%;
            fill: white;
        }

        .category-title {
            font-size: 2rem;
            color: #2c5282;
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-description {
            margin-bottom: 2rem;
            color: #666;
            line-height: 1.6;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 15px rgba(44, 82, 130, 0.2);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #2c5282;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .product-card:hover::before {
            transform: scaleX(1);
        }

        .product-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #f6f6f6 0%, #e9e9e9 100%);
            border-radius: 4px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-height: none;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c5282;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .product-button {
            background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            transform: translateY(0);
            font-weight: 500;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .product-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(44, 82, 130, 0.3);
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            position: relative;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            margin: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .product-header {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1rem;
        }

        .product-header .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #ebf4ff;
            color: #2c5282;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            width: fit-content;
        }

        .modal-image-container {
            position: relative;
            margin: 2rem 0;
            text-align: center;
            background-color: #f7fafc;
            padding: 1rem;
            border-radius: 8px;
            overflow: hidden;
        }

        .modal-image-container img {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        .modal-image-container:hover img {
            transform: scale(1.02);
        }

        .info-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .info-section h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2c5282;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .info-section .product-description,
        .info-section .product-details {
            color: #4a5568;
            line-height: 1.8;
            font-size: 1rem;
        }

        .close-modal {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f7fafc;
            color: #a0aec0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            outline: none;
            z-index: 10;
        }

        .close-modal:hover {
            background-color: #e2e8f0;
            color: #2c5282;
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 1rem;
                padding: 1.5rem;
            }

            .info-section {
                padding: 1rem;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        .scroll-animation {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .scroll-animation.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Add preloader styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .preloader.fade-out {
            opacity: 0;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2c5282;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        .loading-text {
            color: #2c5282;
            font-size: 1.2rem;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Search bar styles */
        .search-container {
            max-width: 600px;
            margin: 0 auto 3rem auto;
            padding: 0 1rem;
            position: sticky;
            top: 1rem;
            z-index: 100;
        }

        .search-wrapper {
            position: relative;
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .search-wrapper:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            width: 100%;
            padding: 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: white;
            color: #4a5568;
        }

        .search-input:focus {
            outline: none;
            border-color: #2c5282;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .clear-search {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: none;
            background: none;
            border: none;
            outline: none;
        }

        .clear-search:hover {
            color: #2c5282;
            background-color: #f7fafc;
        }

        .clear-search.visible {
            display: block;
        }

        .search-input:focus + .search-icon {
            color: #2c5282;
        }

        .search-stats {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .search-stats.visible {
            opacity: 1;
        }

        .no-results-global {
            text-align: center;
            padding: 3rem 1rem;
            color: #4a5568;
            font-size: 1.2rem;
            background: white;
            border-radius: 8px;
            margin: 2rem auto;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: none;
        }

        .no-results-global.visible {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .no-results-global svg {
            width: 64px;
            height: 64px;
            color: #a0aec0;
            margin-bottom: 1rem;
        }

        .no-results-global h3 {
            color: #2c5282;
            margin-bottom: 0.5rem;
        }

        .no-results-global p {
            color: #718096;
            margin-bottom: 1rem;
        }

        .no-results-global .suggestions {
            font-size: 0.95rem;
            color: #4a5568;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .no-results-global .suggestions ul {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0;
        }

        .no-results-global .suggestions li {
            margin: 0.25rem 0;
            color: #718096;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .search-container {
                position: sticky;
                top: 0;
                background: #f5f5f5;
                padding: 1rem;
                margin-bottom: 2rem;
            }

            .search-input {
                font-size: 16px; /* Prevent zoom on mobile */
                padding: 0.875rem 2.5rem;
            }

            .search-icon, .clear-search {
                width: 18px;
                height: 18px;
            }

            .search-stats {
                font-size: 0.8rem;
            }
        }

        .product-card {
            transition: all 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
        }

        .product-card.hidden {
            display: none;
        }

        .product-card.filtered {
            animation: fadeIn 0.3s ease;
        }

        .category-section {
            transition: opacity 0.3s ease;
        }

        .category-section.filtered {
            animation: fadeIn 0.3s ease;
        }

        /* Enhanced responsive styles */
        @media (max-width: 1200px) {
            .products-container {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            body {
                padding: 1.5rem;
            }

            .category-title {
                font-size: 1.75rem;
            }

            .product-card {
                padding: 1.25rem;
            }

            .product-image {
                height: 250px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.75rem;
            }

            .category-header {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .category-logo {
                margin: 0 auto;
            }

            .modal-content {
                padding: 1.5rem;
                margin: 1rem;
            }

            .product-image {
                height: 200px;
            }

            .search-container {
                position: sticky;
                top: 0;
                padding: 0.75rem;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .products-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .product-card {
                padding: 1rem;
            }

            .modal-content {
                padding: 1rem;
                margin: 0.5rem;
            }

            .info-section {
                padding: 0.75rem;
            }

            .product-button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .category-description {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 360px) {
            body {
                padding: 0.5rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .back-button {
                padding: 6px 12px;
                font-size: 0.9rem;
            }

            .product-image {
                height: 180px;
            }

            .product-title {
                font-size: 1.1rem;
            }
        }

        /* Add support for dark mode */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a202c;
                color: #e2e8f0;
            }

            .product-card,
            .modal-content,
            .search-wrapper,
            .search-input,
            .no-results-global {
                background-color: #2d3748;
                color: #e2e8f0;
            }

            .product-title {
                color: #e2e8f0;
            }

            .product-description,
            .category-description {
                color: #a0aec0;
            }

            .search-input {
                border-color: #4a5568;
                color: #e2e8f0;
            }

            .search-input:focus {
                border-color: #4299e1;
            }

            .info-section {
                background-color: #2d3748;
                border-color: #4a5568;
            }

            .close-modal {
                background-color: #4a5568;
                color: #e2e8f0;
            }

            .close-modal:hover {
                background-color: #2d3748;
            }
        }

        /* Add smooth transitions for dark mode */
        body,
        .product-card,
        .modal-content,
        .search-wrapper,
        .search-input,
        .no-results-global,
        .info-section,
        .close-modal {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .no-products-message {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 2rem auto;
            max-width: 400px;
            transition: all 0.3s ease;
        }

        .no-products-message i {
            font-size: 3rem;
            color: #a0aec0;
            margin-bottom: 1rem;
        }

        .no-products-message p {
            font-size: 1.2rem;
            color: #2c5282;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .no-products-message span {
            color: #718096;
            font-size: 0.95rem;
        }

        /* Dark mode support for no-products-message */
        @media (prefers-color-scheme: dark) {
            .no-products-message {
                background-color: #2d3748;
            }

            .no-products-message p {
                color: #e2e8f0;
            }

            .no-products-message span {
                color: #a0aec0;
            }
        }

        /* Quotation Form Styles */
        .quotation-form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .quotation-form-header h2 {
            color: #2c5282;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .quotation-form-header p {
            color: #718096;
            font-size: 0.95rem;
        }

        .quotation-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            color: #2c5282;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label i {
            color: #4299e1;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f7fafc;
            cursor: not-allowed;
        }

        .form-text {
            font-size: 0.875rem;
            color: #718096;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(44, 82, 130, 0.3);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-footer {
                flex-direction: column-reverse;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .quotation-form-header h2 {
                color: #4299e1;
            }

            .form-control {
                background-color: #2d3748;
                border-color: #4a5568;
                color: #e2e8f0;
            }

            .form-control:focus {
                border-color: #4299e1;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }

            .form-control[readonly] {
                background-color: #1a202c;
            }

            .btn-secondary {
                background: #4a5568;
                color: #e2e8f0;
            }

            .btn-secondary:hover {
                background: #2d3748;
            }
        }

        /* Notification styles */
        .notification {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification.success {
            border-left: 4px solid #48bb78;
        }

        .notification.error {
            border-left: 4px solid #f56565;
        }

        .notification i {
            font-size: 1.25rem;
        }

        .notification.success i {
            color: #48bb78;
        }

        .notification.error i {
            color: #f56565;
        }

        .notification p {
            margin: 0;
            color: #4a5568;
        }

        @media (max-width: 768px) {
            .notification {
                left: 1rem;
                right: 1rem;
                bottom: 1rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            .notification {
                background: #2d3748;
            }

            .notification p {
                color: #e2e8f0;
            }
        }
    </style>

    <script>
        // ... existing code ...

        function submitQuotation(event) {
            event.preventDefault();
            
            // Get form data
            const form = event.target;
            const formData = {
                product_name: form.querySelector('#quotationProductName').value,
                product_id: currentProduct.id,
                name: form.querySelector('#quotationName').value,
                email: form.querySelector('#quotationEmail').value,
                phone: form.querySelector('#quotationPhone').value,
                hospital_name: form.querySelector('#quotationHospital').value,
                message: form.querySelector('#quotationMessage').value
            };

            // Validate form data
            if (!validateForm(formData)) {
                return;
            }

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Send AJAX request
            fetch('/api/quotation-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Your quotation request has been sent successfully! We will contact you soon.');
                    form.reset();
                    closeQuotationModal();
                } else {
                    throw new Error(data.message || 'Failed to send request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'There was an error sending your request. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        }

        function validateForm(data) {
            const nameRegex = /^[A-Za-z\s]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const phoneRegex = /^[0-9\+\-\s]+$/;

            if (!nameRegex.test(data.name)) {
                showNotification('error', 'Please enter a valid name (letters and spaces only)');
                return false;
            }

            if (!emailRegex.test(data.email)) {
                showNotification('error', 'Please enter a valid email address');
                return false;
            }

            if (!phoneRegex.test(data.phone) || data.phone.length < 10) {
                showNotification('error', 'Please enter a valid phone number');
                return false;
            }

            if (data.hospital_name.length < 3) {
                showNotification('error', 'Hospital name must be at least 3 characters long');
                return false;
            }

            return true;
        }

        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <p>${message}</p>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Character counter for message field
        document.getElementById('quotationMessage').addEventListener('input', function(e) {
            const counter = document.getElementById('messageCounter');
            counter.textContent = `${e.target.value.length}/500`;
        });
    </script>
</div>
</script>

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .quotation-form-header h2 {
                color: #4299e1;
            }

            .form-control {
                background-color: #2d3748;
                border-color: #4a5568;
                color: #e2e8f0;
            }

            .form-control:focus {
                border-color: #4299e1;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }

            .form-control[readonly] {
                background-color: #1a202c;
            }

            .btn-secondary {
                background: #4a5568;
                color: #e2e8f0;
            }

            .btn-secondary:hover {
                background: #2d3748;
            }
        }

        /* Notification styles */
        .notification {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification.success {
            border-left: 4px solid #48bb78;
        }

        .notification.error {
            border-left: 4px solid #f56565;
        }

        .notification i {
            font-size: 1.25rem;
        }

        .notification.success i {
            color: #48bb78;
        }

        .notification.error i {
            color: #f56565;
        }

        .notification p {
            margin: 0;
            color: #4a5568;
        }

        @media (max-width: 768px) {
            .notification {
                left: 1rem;
                right: 1rem;
                bottom: 1rem;
            }
        }

        @media (prefers-color-scheme: dark) {
            .notification {
                background: #2d3748;
            }

            .notification p {
                color: #e2e8f0;
            }
        }
    </style>
</head>
<body>
    <!-- Add preloader HTML right after body tag -->
    <div class="preloader">
        <div class="spinner"></div>
        <div class="loading-text">Loading...</div>
    </div>

    <div class="header">
        <button onclick="window.location.href='/mhrhealthcareinc'" class="back-button"><i class="fas fa-arrow-left"></i> Back</button>
        <h1>Our Products</h1>
    </div>

    <!-- Add search bar -->
    <div class="search-container">
        <div class="search-wrapper">
            <input type="text" class="search-input" placeholder="Search products by name, description..." id="searchInput" autocomplete="off">
            <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.5 3C11.5376 3 14 5.46243 14 8.5C14 9.83879 13.5217 11.0659 12.7266 12.0196L16.8536 16.1464C17.0488 16.3417 17.0488 16.6583 16.8536 16.8536C16.6583 17.0488 16.3417 17.0488 16.1464 16.8536L12.0196 12.7266C11.0659 13.5217 9.83879 14 8.5 14C5.46243 14 3 11.5376 3 8.5C3 5.46243 5.46243 3 8.5 3ZM8.5 4C6.01472 4 4 6.01472 4 8.5C4 10.9853 6.01472 13 8.5 13C10.9853 13 13 10.9853 13 8.5C13 6.01472 10.9853 4 8.5 4Z" fill="currentColor"/>
            </svg>
            <button class="clear-search" id="clearSearch" title="Clear search">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2ZM7.70711 7.70711C7.31658 7.31658 6.68342 7.31658 6.29289 7.70711C5.90237 8.09763 5.90237 8.73079 6.29289 9.12132L7.17157 10L6.29289 10.8787C5.90237 11.2692 5.90237 11.9024 6.29289 12.2929C6.68342 12.6834 7.31658 12.6834 7.70711 12.2929L8.58579 11.4142L9.46447 12.2929C9.85499 12.6834 10.4882 12.6834 10.8787 12.2929C11.2692 11.9024 11.2692 11.2692 10.8787 10.8787L10 10L10.8787 9.12132C11.2692 8.73079 11.2692 8.09763 10.8787 7.70711C10.4882 7.31658 9.85499 7.31658 9.46447 7.70711L8.58579 8.58579L7.70711 7.70711Z" fill="currentColor"/>
                </svg>
            </button>
        </div>
        <div class="search-stats" id="searchStats"></div>
    </div>

    <!-- Add global no results message -->
    <div class="no-results-global" id="noResultsGlobal">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h3>No Products Found</h3>
        <p>We couldn't find any medical products matching your search criteria.</p>
        <div class="suggestions">
            <strong>Suggestions:</strong>
            <ul>
                <li>Check for spelling errors</li>
                <li>Try using more general terms</li>
                <li>Try searching by product category</li>
                <li>Remove filters to broaden your search</li>
            </ul>
        </div>
    </div>

    @foreach($categories as $category)
    <div id="{{ Str::slug($category->name) }}" class="category-section">
        <div class="category-header">
            <img src="{{ asset('storage/' . $category->logo) }}" alt="{{ $category->name }} Icon" style="max-width: 100px; height: auto;">
            <h2 class="category-title">{{ $category->name }}</h2>
        </div>
        <p class="category-description">{{ $category->description }}</p>
        <div class="products-container" id="{{ Str::slug($category->name) }}Container">
            @forelse($medicalProducts->where('category_id', $category->id) as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
                <h3 class="product-title">{{ $product->name }}</h3>
                <p class="product-description">{{ Str::limit($product->description, 150) }}</p>
                <button class="product-button" onclick="showProductDetails(this)" 
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-image="{{ asset('storage/' . $product->image) }}"
                    data-description="{{ $product->description }}"
                    data-details="{{ $product->details }}"
                    data-category="{{ $category->name }}">
                    View Details
                </button>
            </div>
            @empty
            <div class="no-products-message">
                <i class="fas fa-box-open"></i>
                <p>No products found in this category</p>
                <span>Check back later for new additions</span>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach

    <div class="modal" id="productModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()" title="Close">
                <i class="fas fa-times"></i>
            </span>
            <div id="modalContent"></div>
            <div class="modal-actions" style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <button class="product-button" onclick="showQuotationModal()" style="width: auto;">
                    <i class="fas fa-file-invoice"></i> Request Quotation
                </button>
            </div>
        </div>
    </div>

    <!-- Add new quotation modal -->
    <div class="modal" id="quotationModal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeQuotationModal()">
                <i class="fas fa-times"></i>
            </span>
            
            <div class="quotation-form-header">
                <h2><i class="fas fa-file-invoice"></i> Request Quotation</h2>
                <p>Please fill in the details below to request a quotation for the selected product.</p>
            </div>

            <form id="quotationForm" onsubmit="submitQuotation(event)" class="quotation-form">
                <div class="form-group">
                    <label for="quotationProductName">
                        <i class="fas fa-box"></i> Product Name
                    </label>
                    <input type="text" id="quotationProductName" readonly class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quotationName">
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input type="text" 
                               id="quotationName" 
                               name="name" 
                               class="form-control" 
                               placeholder="Enter your full name"
                               required
                               minlength="3"
                               pattern="[A-Za-z\s]+"
                               title="Please enter a valid name (letters and spaces only)">
                    </div>

                    <div class="form-group">
                        <label for="quotationEmail">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" 
                               id="quotationEmail" 
                               name="email" 
                               class="form-control" 
                               placeholder="Enter your email address"
                               required
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               title="Please enter a valid email address">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quotationPhone">
                            <i class="fas fa-phone"></i> Contact Number
                        </label>
                        <input type="tel" 
                               id="quotationPhone" 
                               name="phone" 
                               class="form-control" 
                               placeholder="Enter your contact number"
                               required
                               pattern="[0-9\+\-\s]+"
                               title="Please enter a valid phone number"
                               minlength="10">
                        <small class="form-text">Format: +63 XXX XXX XXXX or 09XX XXX XXXX</small>
                    </div>

                    <div class="form-group">
                        <label for="quotationHospital">
                            <i class="fas fa-hospital"></i> Hospital/Institution Name
                        </label>
                        <input type="text" 
                               id="quotationHospital" 
                               name="hospital_name" 
                               class="form-control" 
                               placeholder="Enter your hospital/institution name"
                               required
                               minlength="3">
                    </div>
                </div>

                <div class="form-group">
                    <label for="quotationMessage">
                        <i class="fas fa-comment"></i> Additional Message (Optional)
                    </label>
                    <textarea id="quotationMessage" 
                              name="message" 
                              class="form-control" 
                              placeholder="Enter any additional information or specific requirements"
                              rows="3"
                              maxlength="500"></textarea>
                    <small class="form-text text-right" id="messageCounter">0/500</small>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeQuotationModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            preloader.classList.add('fade-out');
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        });

        // Initialize products data from PHP
        const productsData = {!! 
            json_encode(
                $categories->mapWithKeys(function($category) use ($medicalProducts) {
                    return [
                        Str::slug($category->name) => $medicalProducts
                            ->where('category_id', $category->id)
                            ->map(function($product) {
                                return [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'image' => asset('storage/' . $product->image),
                                    'description' => $product->description,
                                    'details' => $product->details
                                ];
                            })->values()->all()
                    ];
                })->all(), 
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
            ) 
        !!};
        const products = JSON.parse(JSON.stringify(productsData));

        let currentProduct = null;

        function showProductDetails(button) {
            const product = {
                id: button.dataset.id,
                name: button.dataset.name,
                image: button.dataset.image,
                description: button.dataset.description,
                details: button.dataset.details,
                category: button.dataset.category
            };

            currentProduct = { id: product.id, name: product.name };
            const modal = document.getElementById('productModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.innerHTML = `
                <div class="product-header">
                    <div class="category-badge">
                        <i class="fas fa-tag"></i>
                        ${product.category}
                    </div>
                    <h2 class="product-title">${product.name}</h2>
                </div>

                <div class="modal-image-container">
                    <img src="${product.image}" alt="${product.name}" loading="lazy">
                </div>

                <div class="product-info">
                    <div class="info-section">
                        <h3>
                            <i class="fas fa-info-circle"></i>
                            Product Description
                        </h3>
                        <p class="product-description">${product.description}</p>
                    </div>
                    
                    <div class="info-section">
                        <h3>
                            <i class="fas fa-wrench"></i>
                            Specifications
                        </h3>
                        <div class="product-details">
                            ${product.details ? formatDetails(product.details) : '<p>No technical specifications available.</p>'}
                        </div>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Add zoom effect to modal image
            const modalImage = modalContent.querySelector('.modal-image-container img');
            if (modalImage) {
                modalImage.addEventListener('load', () => {
                    modalImage.style.opacity = '1';
                });
            }
        }

        function formatDetails(details) {
            // Check if details is already in HTML format
            if (details.includes('<') && details.includes('>')) {
                return details;
            }

            // Split by new lines and create a list
            const lines = details.split('\n').filter(line => line.trim());
            if (lines.length === 0) return '<p>No technical specifications available.</p>';

            if (lines.length === 1) return `<p>${lines[0]}</p>`;

            return `<ul style="list-style: none; padding: 0; margin: 0;">
                ${lines.map(line => `
                    <li style="padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center;">
                        <i class="fas fa-check" style="color: #2c5282; margin-right: 0.75rem;"></i>
                        ${line}
                    </li>
                `).join('')}
            </ul>`;
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Clear the modal content after animation
            setTimeout(() => {
                document.getElementById('modalContent').innerHTML = '';
            }, 300);
        }

        function showQuotationModal() {
            if (!currentProduct) return;
            
            const quotationModal = document.getElementById('quotationModal');
            const productNameInput = document.getElementById('quotationProductName');
            
            productNameInput.value = currentProduct.name;
            quotationModal.classList.add('active');
            closeModal();
        }

        function closeQuotationModal() {
            const modal = document.getElementById('quotationModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function submitQuotation(event) {
            event.preventDefault();
            
            // Get form data
            const form = event.target;
            const formData = {
                product_name: form.querySelector('#quotationProductName').value,
                product_id: currentProduct.id,
                name: form.querySelector('#quotationName').value,
                email: form.querySelector('#quotationEmail').value,
                phone: form.querySelector('#quotationPhone').value,
                hospital_name: form.querySelector('#quotationHospital').value,
                message: form.querySelector('#quotationMessage').value
            };

            // Validate form data
            if (!validateForm(formData)) {
                return;
            }

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Send AJAX request
            fetch('/api/quotation-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Your quotation request has been sent successfully! We will contact you soon.');
                    form.reset();
                    closeQuotationModal();
                } else {
                    throw new Error(data.message || 'Failed to send request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'There was an error sending your request. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        }

        function validateForm(data) {
            const nameRegex = /^[A-Za-z\s]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const phoneRegex = /^[0-9\+\-\s]+$/;

            if (!nameRegex.test(data.name)) {
                showNotification('error', 'Please enter a valid name (letters and spaces only)');
                return false;
            }

            if (!emailRegex.test(data.email)) {
                showNotification('error', 'Please enter a valid email address');
                return false;
            }

            if (!phoneRegex.test(data.phone) || data.phone.length < 10) {
                showNotification('error', 'Please enter a valid phone number');
                return false;
            }

            if (data.hospital_name.length < 3) {
                showNotification('error', 'Hospital name must be at least 3 characters long');
                return false;
            }

            return true;
        }

        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <p>${message}</p>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Character counter for message field
        document.getElementById('quotationMessage').addEventListener('input', function(e) {
            const counter = document.getElementById('messageCounter');
            counter.textContent = `${e.target.value.length}/500`;
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clearSearchButton = document.getElementById('clearSearch');
        const searchStats = document.getElementById('searchStats');
        const noResultsGlobal = document.getElementById('noResultsGlobal');

        function updateSearchStats(visibleProducts) {
            const total = document.querySelectorAll('.product-card').length;
            searchStats.textContent = `Showing ${visibleProducts} of ${total} products`;
            searchStats.classList.toggle('visible', searchInput.value.length > 0);
        }

        function filterProducts(searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            let visibleProducts = 0;
            let visibleCategories = 0;

            Object.keys(products).forEach(categoryId => {
                const categorySection = document.getElementById(categoryId);
                let hasVisibleProducts = false;

                const productCards = categorySection.querySelectorAll('.product-card');
                productCards.forEach(card => {
                    const name = card.querySelector('.product-title').textContent.toLowerCase();
                    const description = card.querySelector('.product-description').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || description.includes(searchTerm)) {
                        card.classList.remove('hidden');
                        card.classList.add('filtered');
                        visibleProducts++;
                        hasVisibleProducts = true;
                    } else {
                        card.classList.add('hidden');
                        card.classList.remove('filtered');
                    }
                });

                categorySection.style.display = hasVisibleProducts ? '' : 'none';
                if (hasVisibleProducts) visibleCategories++;
            });

            updateSearchStats(visibleProducts);
            noResultsGlobal.classList.toggle('visible', visibleProducts === 0);
            
            return visibleProducts;
        }

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim();
            clearSearchButton.classList.toggle('visible', searchTerm.length > 0);
            filterProducts(searchTerm);
        });

        clearSearchButton.addEventListener('click', () => {
            searchInput.value = '';
            clearSearchButton.classList.remove('visible');
            filterProducts('');
            searchStats.classList.remove('visible');
            noResultsGlobal.classList.remove('visible');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            const productModal = document.getElementById('productModal');
            const quotationModal = document.getElementById('quotationModal');
            
            if (e.target === productModal) {
                closeModal();
            } else if (e.target === quotationModal) {
                closeQuotationModal();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
                closeQuotationModal();
            }
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.product-card, .category-section').forEach(el => {
            el.classList.add('scroll-animation');
            observer.observe(el);
        });
    </script>
</body>
</html>
