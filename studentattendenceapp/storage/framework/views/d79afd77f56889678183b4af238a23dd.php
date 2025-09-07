›
<?php use Illuminate\Support\Facades\Auth; ?>›

<?php $__env->startSection('content'); ?>
<style>
    body {
        background: radial-gradient(circle at top left, #4f46e5, #3b82f6);
        font-family: 'Segoe UI', sans-serif;
        color: #fff;
        min-height: 100vh;
    }

    .hero-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5rem 1rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        padding: 3rem;
        text-align: center;
        max-width: 800px;
        width: 100%;
        animation: popUp 0.8s ease;
    }

    @keyframes popUp {
        0% {
            opacity: 0;
            transform: scale(0.95) translateY(20px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        background: linear-gradient(to right, #ffffff, #e0e0ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        font-weight: 400;
        color: #e0e7ff;
        margin-bottom: 2rem;
    }

    .cta-buttons a {
        font-size: 1.1rem;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        transition: 0.3s ease-in-out;
        margin: 0.5rem;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-glow {
        background: linear-gradient(135deg, #a78bfa, #6366f1);
        color: white;
        border: none;
    }

    .btn-glow:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
    }

    .btn-outline-glow {
        border: 2px solid #a5b4fc;
        color: #e0e7ff;
        background: transparent;
    }

    .btn-outline-glow:hover {
        background: #a5b4fc;
        color: #1e1b4b;
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 10px 20px rgba(165, 180, 252, 0.4);
    }

    .alert-box {
        background-color: rgba(34, 197, 94, 0.15);
        border-left: 4px solid #22c55e;
        padding: 1rem 1.5rem;
        color: #d1fae5;
        border-radius: 1rem;
    }
</style>

<div class="hero-container">
    <div class="glass-card">

        <!-- Header -->
        <h1 class="hero-title">🎓 Student Attendance System</h1>
        <p class="hero-subtitle">Smart. Beautiful. Powerful. Attendance made easy for everyone.</p>

        <!-- Guest -->
        <?php if(auth()->guard()->guest()): ?>
            <div class="cta-buttons d-flex flex-wrap justify-content-center">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-glow">🔐 Login</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-glow">✍️ Register</a>
            </div>
        <?php endif; ?>

        <!-- Logged In -->
        <?php if(auth()->guard()->check()): ?>
            <div class="alert-box mt-4">
                👋 Hello, <strong><?php echo e(Auth::user()->name); ?></strong>! You're already logged in.
                <div class="mt-2">
                    <a href="<?php echo e(url('/redirect')); ?>" class="btn btn-glow btn-sm mt-2">Go to Dashboard →</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/welcome.blade.php ENDPATH**/ ?>