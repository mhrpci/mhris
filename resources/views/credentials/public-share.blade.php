<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Credentials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .table-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-top: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .header {
            background: transparent;
            color: #2b5876;
            padding: 30px 0 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .logo-container {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
        }
        .logo {
            max-height: 180px;
            margin-bottom: 20px;
            filter: drop-shadow(0 8px 15px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
        .app-name {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
        .app-subtitle {
            font-size: 1.4rem;
            color: #4e4376;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .copy-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            margin-top: 35px;
            padding: 20px;
            color: #6c757d;
            font-size: 0.9rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .alert-info {
            background-color: #e7f3ff;
            border-color: #b8daff;
            color: #0c5460;
            border-radius: 8px;
            padding: 15px;
        }
        .table-container h4 {
            color: #2b5876;
            font-weight: 600;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 12px;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            color: white;
            border: none;
            padding: 12px 15px;
        }
        .table thead th:first-child {
            border-top-left-radius: 8px;
        }
        .table thead th:last-child {
            border-top-right-radius: 8px;
        }
        .table tbody tr:hover {
            background-color: rgba(43, 88, 118, 0.03);
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            border-radius: 6px;
            overflow: hidden;
        }
        .form-control {
            border: 1px solid #e7f3ff;
            font-family: monospace;
            font-size: 1rem;
        }
        .toggle-password {
            background-color: #f8f9fa;
            border-color: #e7f3ff;
        }
        .btn-outline-secondary:hover {
            background-color: #e7f3ff;
            color: #0c5460;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .separate-copy-btns {
            display: flex;
            gap: 5px;
        }
        .separate-copy-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-input {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e7f3ff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        .badge-password {
            font-family: monospace;
            letter-spacing: 0.5px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
            }
            .header {
                padding: 20px 0 15px;
            }
            .app-name {
                font-size: 2.2rem;
            }
            .app-subtitle {
                font-size: 1.2rem;
            }
            .table thead th {
                font-size: 0.9rem;
                padding: 10px 8px;
            }
            .table tbody td {
                font-size: 0.9rem;
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="header text-center">
            <div class="logo-container">
                <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="{{ env('APP_NAME') }} Logo" class="logo">
            </div>
            <h1 class="app-name">{{ env('APP_NAME') }}</h1>
            <p class="app-subtitle">Shared Credentials Portal</p>
            <p class="mb-0">This link will expire in <span id="countdown" class="countdown">{{ $remainingTime }}</span> minutes</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    This is a temporary link to access employee credentials. Please use this information responsibly.
                </div>

                <div class="table-container">
                    <div class="mb-4">
                        <h4>Employee Credentials</h4>
                        @if($shareableLink->description)
                            <p class="text-muted mt-2">{{ $shareableLink->description }}</p>
                        @endif
                    </div>
                    
                    <div class="search-container">
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Search by employee name, email or number...">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="credentialsTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Employee</th>
                                    <th width="25%">Company Email</th>
                                    <th width="20%">Email Password</th>
                                    <th width="15%">Company Number</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($credentials as $index => $credential)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-medium">
                                                {{ $credential->employee->first_name ?? '' }} {{ $credential->employee->last_name ?? '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <span class="fw-medium">{{ $credential->company_email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="password" class="form-control password-field" value="{{ $credential->email_password }}" readonly>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" title="Show/Hide Password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-phone me-2 text-success"></i>
                                                <span class="fw-medium">{{ $credential->company_number }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="separate-copy-btns">
                                                <button class="btn btn-sm btn-info copy-email-btn" data-email="{{ $credential->company_email }}" title="Copy Email">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning copy-password-btn" data-password="{{ $credential->email_password }}" title="Copy Password">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary copy-all-btn" 
                                                    data-email="{{ $credential->company_email }}" 
                                                    data-password="{{ $credential->email_password }}"
                                                    data-number="{{ $credential->company_number }}"
                                                    title="Copy All Details">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x mb-3 text-muted"></i>
                                            <p class="mb-0">No credentials found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="footer">
                    <p class="mb-0">This link was generated {{ $shareableLink->created_at->diffForHumans() }} and will expire {{ $shareableLink->expires_at->diffForHumans() }}.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                var passwordField = $(this).closest('.input-group').find('.password-field');
                var icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Copy email
            $('.copy-email-btn').click(function() {
                copyToClipboard($(this).data('email'));
                showCopySuccess($(this));
            });
            
            // Copy password
            $('.copy-password-btn').click(function() {
                copyToClipboard($(this).data('password'));
                showCopySuccess($(this));
            });
            
            // Copy all details
            $('.copy-all-btn').click(function() {
                var email = $(this).data('email');
                var password = $(this).data('password');
                var number = $(this).data('number');
                
                var textToCopy = 'Email: ' + email + '\nPassword: ' + password + '\nNumber: ' + number;
                copyToClipboard(textToCopy);
                showCopySuccess($(this));
            });
            
            function copyToClipboard(text) {
                var tempElement = $('<textarea>');
                $('body').append(tempElement);
                tempElement.val(text).select();
                document.execCommand('copy');
                tempElement.remove();
            }
            
            function showCopySuccess(button) {
                var originalColor = button.css('background-color');
                var originalHtml = button.html();
                
                button.html('<i class="fas fa-check"></i>').addClass('btn-success').removeClass('btn-primary btn-info btn-warning');
                
                setTimeout(function() {
                    button.html(originalHtml).removeClass('btn-success');
                    if (button.hasClass('copy-email-btn')) button.addClass('btn-info');
                    else if (button.hasClass('copy-password-btn')) button.addClass('btn-warning');
                    else button.addClass('btn-primary');
                }, 1500);
            }
            
            // Countdown timer
            var minutes = parseInt($('#countdown').text());
            var seconds = minutes * 60;
            
            function updateCountdown() {
                var minutesLeft = Math.floor(seconds / 60);
                var secondsLeft = seconds % 60;
                
                $('#countdown').text(minutesLeft + ':' + (secondsLeft < 10 ? '0' : '') + secondsLeft);
                
                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    location.reload(); // Reload page when time expires
                } else {
                    seconds--;
                }
            }
            
            var countdownTimer = setInterval(updateCountdown, 1000);
            updateCountdown(); // Initialize countdown
            
            // Search functionality
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#credentialsTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>
</html> 