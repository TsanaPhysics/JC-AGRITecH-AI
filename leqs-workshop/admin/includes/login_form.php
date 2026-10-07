    <!-- ========================================================================= -->
    <!-- 1. LOGIN SCREEN (EXACT REPLICA OF scirbru_praneet2026/admin_index.php)    -->
    <!-- ========================================================================= -->
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-gray-950">
        <!-- Background Ambient Glows -->
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all relative z-10">
            
            <!-- Card Header: Dark gradient with glow -->
            <div class="bg-gradient-to-br from-gray-800 via-gray-900 to-black p-8 sm:p-10 text-center relative overflow-hidden">
                <!-- Decorative Circles -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-cyan-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 text-white">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 backdrop-blur-md shadow-lg">
                        <i class="fas fa-lock text-emerald-400 text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight uppercase font-tech">Admin Login</h1>
                    <p class="text-gray-400 text-xs sm:text-sm mt-1">ระบบบริหารจัดการ ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม</p>
                    <p class="text-[11px] text-emerald-400/90 font-medium mt-0.5">คณะวิทยาศาสตร์และเทคโนโลยี มรภ.รำไพพรรณี</p>
                </div>
            </div>

            <!-- Card Body: Clean White Form -->
            <div class="p-8">
                <?php if (!empty($error)): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation text-sm shrink-0"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="login">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest pl-1 font-tech">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <i class="fas fa-user-circle text-base"></i>
                            </span>
                            <input type="text" name="username" required autofocus value="admin"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all text-gray-800 text-sm"
                                placeholder="กรอกชื่อผู้ใช้">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest pl-1 font-tech">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <i class="fas fa-key text-base"></i>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none transition-all text-gray-800 text-sm"
                                placeholder="Default: LEQs">
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 rounded-xl shadow-xl shadow-gray-300 transform transition active:scale-95 flex items-center justify-center gap-2 text-sm">
                        เข้าสู่ระบบ <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-4 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-gray-500 font-mono">
                    Default Credentials: <strong class="text-emerald-600 font-bold">admin</strong> / <strong class="text-emerald-600 font-bold">LEQs</strong>
                </div>

                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-gray-400 hover:text-gray-700 text-xs flex items-center justify-center gap-1.5 group transition">
                        <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i> กลับสู่เว็บไซต์หลัก
                    </a>
                </div>
            </div>
        </div>
    </div>

