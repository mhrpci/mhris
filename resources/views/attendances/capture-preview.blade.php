@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    body.preview-active {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    
    .preview-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #000;
        z-index: 9990;
    }
    
    /* Image preview section */
    .image-preview-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background-color: #000;
    }
    
    .preview-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Logo overlay */
    .preview-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 80px;
        height: auto;
        z-index: 9992;
        background: rgba(255, 255, 255, 0.9);
        padding: 6px;
        border-radius: 8px;
    }
    
    /* Clock In/Out Badge */
    .preview-status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #2ecc71;
        padding: 8px 16px;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        z-index: 9992;
        font-size: 1rem;
    }
    
    .preview-status-badge.out {
        background: #e74c3c;
    }
    
    /* Info overlay */
    .preview-info-overlay {
        position: absolute;
        left: 20px;
        bottom: 20px;
        width: calc(100% - 40px);
        color: white;
        z-index: 9992;
    }
    
    .preview-time {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .preview-date {
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    .preview-location {
        font-size: 1.1rem;
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .preview-employee-info {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .preview-name {
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .preview-company {
        margin-bottom: 8px;
    }
    
    .preview-position {
        margin-bottom: 8px;
    }
    
    /* Action buttons */
    .preview-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        background: rgba(0, 0, 0, 0.8);
        gap: 1rem;
        z-index: 9992;
    }
    
    .btn-retake, .btn-confirm {
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-retake {
        background: #6c757d;
        color: white;
    }
    
    .btn-confirm {
        background: #2ecc71;
        color: white;
    }
    
    /* Loading overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .preview-logo {
            width: 60px;
            top: 15px;
            right: 15px;
        }
        
        .preview-status-badge {
            font-size: 0.9rem;
            padding: 6px 12px;
        }
        
        .preview-time {
            font-size: 1.6rem;
        }
        
        .preview-date {
            font-size: 1rem;
        }
        
        .preview-location,
        .preview-employee-info {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .preview-logo {
            width: 50px;
            top: 10px;
            right: 10px;
        }
        
        .preview-status-badge {
            font-size: 0.8rem;
            padding: 4px 10px;
        }
        
        .preview-time {
            font-size: 1.4rem;
        }
        
        .preview-date {
            font-size: 0.9rem;
        }
        
        .preview-location,
        .preview-employee-info {
            font-size: 0.85rem;
        }
        
        .btn-retake, .btn-confirm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="preview-container">
    <div class="image-preview-container">
        <img id="preview-image" class="preview-image" src="" alt="Attendance Capture">
        
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
        
        <div id="preview-status-badge" class="preview-status-badge">
            <i class="fas fa-clock"></i>
            <span id="status-text">Clock In</span>
        </div>
        
        <div class="preview-info-overlay">
            <div class="preview-time" id="preview-time">07:56</div>
            <div class="preview-date" id="preview-date">Fri, Mar 14, 2025</div>
            <div class="preview-location">
                <div>Jose L Briones Street, Lungsod ng Cebu,</div>
                <div>6000 Lalawigan ng Cebu</div>
            </div>
            <div class="preview-employee-info">
                <div class="preview-name" id="preview-name">Name: Edmar Crescencio</div>
                <div class="preview-company" id="preview-company">Company: MHR Property Conglomerates, Inc.</div>
                <div class="preview-position" id="preview-position">Position: IT Staff - Admin Department</div>
            </div>
        </div>
        
        <div class="preview-actions">
            <button class="btn-retake" onclick="goBack()">
                <i class="fas fa-redo"></i>
                Retake
            </button>
            <button class="btn-confirm" onclick="confirmAttendance()">
                <i class="fas fa-check"></i>
                Confirm
            </button>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            document.body.classList.add('preview-active');
            
            // Get data from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const attendanceType = urlParams.get('type') || 'in';
            
            // Get data from localStorage
            const capturedImage = localStorage.getItem('capturedImage');
            const userLocation = localStorage.getItem('userLocation');
            const serverTimestamp = localStorage.getItem('serverTimestamp');
            
            if (!capturedImage || !serverTimestamp) {
                window.location.href = '/attendance';
                return;
            }
            
            // Set the captured image
            document.getElementById('preview-image').src = capturedImage;
            
            // Set the status badge
            const statusBadge = document.getElementById('preview-status-badge');
            const statusText = document.getElementById('status-text');
            
            statusBadge.className = `preview-status-badge ${attendanceType}`;
            statusText.textContent = attendanceType === 'in' ? 'Clock In' : 'Clock Out';
            
            // Get employee information
            await getEmployeeInfo();
            
        } catch (error) {
            console.error('Error initializing preview:', error);
            window.location.href = '/attendance';
        }
    });
    
    async function getEmployeeInfo() {
        try {
            const response = await fetch('/api/employee-info');
            if (!response.ok) throw new Error('Failed to fetch employee information');
            
            const data = await response.json();
            
            document.getElementById('preview-name').textContent = `Name: ${data.name || '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}'}`;
            document.getElementById('preview-position').textContent = `Position: ${data.position || 'Position not available'}`;
            document.getElementById('preview-company').textContent = `Company: ${data.company || 'MHR Property Conglomerates, Inc.'}`;
            
        } catch (error) {
            console.error('Error fetching employee info:', error);
        }
    }
    
    function goBack() {
        document.body.classList.remove('preview-active');
        window.location.href = '/attendance';
    }
    
    async function confirmAttendance() {
        try {
            document.getElementById('loading-overlay').style.display = 'flex';
            
            // Your existing attendance submission logic here
            
            setTimeout(() => {
                document.body.classList.remove('preview-active');
                window.location.href = '/attendance';
            }, 2000);
            
        } catch (error) {
            console.error('Error confirming attendance:', error);
            document.getElementById('loading-overlay').style.display = 'none';
        }
    }
    
    window.addEventListener('beforeunload', () => {
        document.body.classList.remove('preview-active');
    });
</script>
@endsection
