<!-- Global Barcode & QR Scanner Modal -->
<div 
    x-data="barcodeScannerModal()"
    x-show="isOpen" 
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-navy-950/75 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @open-barcode-scanner.window="openScanner($event.detail)"
    @keydown.escape.window="closeScanner()"
    @click.self="closeScanner()">
    
    <div 
        class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh]">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-navy-900" x-text="scannerTitle">Scanner Barcode & QR</h3>
                    <p class="text-[11px] text-slate-500">Scan label hangtag, SKU produk apparel, atau upload foto barcode</p>
                </div>
            </div>
            <button @click="closeScanner()" class="text-slate-400 hover:text-slate-700 text-xl font-bold leading-none p-1">&times;</button>
        </div>

        <!-- 1. Scanner Camera Viewport Area (Active when not having result) -->
        <div x-show="!hasResult" class="p-5 flex flex-col items-center justify-center bg-slate-900 relative min-h-[280px]">
            
            <!-- Video / Camera Container -->
            <div id="barcode-reader" class="w-full rounded-xl overflow-hidden bg-black text-white relative shadow-inner">
            </div>

            <!-- Animated Laser Scan Line Overlay (Active during camera streaming) -->
            <div x-show="isScanning" class="absolute inset-x-8 top-1/2 -translate-y-1/2 pointer-events-none flex flex-col items-center">
                <div class="w-full max-w-[260px] h-[160px] border-2 border-orange-500/80 rounded-xl relative overflow-hidden shadow-[0_0_15px_rgba(249,115,22,0.4)]">
                    <!-- Corner reticles -->
                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-orange-400"></div>
                    <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-orange-400"></div>
                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-orange-400"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-orange-400"></div>
                    <!-- Red/Orange Laser -->
                    <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent animate-laser shadow-[0_0_8px_#f97316]"></div>
                </div>
                <span class="text-[11px] text-orange-400 font-medium mt-2 bg-black/70 px-2.5 py-0.5 rounded-full backdrop-blur-xs">
                    Posisikan barcode / QR dalam kotak
                </span>
            </div>

            <!-- Processing Spinner Overlay -->
            <div x-show="isProcessingFile" class="absolute inset-0 bg-slate-950/85 backdrop-blur-xs flex flex-col items-center justify-center text-white z-20">
                <svg class="animate-spin w-8 h-8 text-orange-500 mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs font-semibold text-slate-200">Menganalisis barcode dari gambar...</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Menerapkan multi-format & filter kontras</p>
            </div>

            <!-- Inline Error / Warning Notification -->
            <div x-show="errorMessage" class="p-3.5 rounded-xl bg-rose-950/85 border border-rose-700/60 text-rose-200 text-xs text-center max-w-sm mt-3 relative z-10">
                <div class="flex items-center justify-center gap-1.5 font-bold text-rose-300 mb-1">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Informasi Scanner</span>
                </div>
                <p x-text="errorMessage" class="text-[11px] leading-relaxed"></p>
            </div>
        </div>

        <!-- 2. Result Preview Screen (Active when barcode is successfully scanned) -->
        <div x-show="hasResult" class="p-6 flex flex-col items-center justify-center text-center bg-white space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-xs border border-emerald-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            
            <div>
                <h4 class="text-sm font-bold text-navy-900">Barcode Berhasil Dipindai!</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Kode barcode terdeteksi dan siap digunakan:</p>
            </div>

            <!-- Scanned Code Display Box -->
            <div class="w-full p-4 bg-slate-50 border-2 border-emerald-500/40 rounded-xl flex items-center justify-between gap-3">
                <div class="text-left flex-1 min-w-0">
                    <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Hasil Scan:</span>
                    <span class="font-mono font-bold text-sm text-navy-900 break-all select-all block mt-0.5" x-text="scannedResult"></span>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-emerald-100 text-emerald-800 flex-shrink-0 border border-emerald-200">
                    ✓ TERDETEKSI
                </span>
            </div>

            <!-- Action Buttons for Result -->
            <div class="w-full flex flex-col sm:flex-row gap-2.5 pt-2">
                <button 
                    type="button" 
                    @click="scanAgain()" 
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Scan Ulang</span>
                </button>

                <button 
                    type="button" 
                    @click="applyResult()" 
                    class="flex-1 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs transition-all shadow-xs flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Gunakan Kode Ini</span>
                </button>
            </div>
        </div>

        <!-- Camera Controls & Upload Alternative (Shown when not having result) -->
        <div x-show="!hasResult" class="p-4 bg-white border-t border-slate-200 space-y-3">
            
            <!-- Camera & Image Buttons -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5">
                    <!-- Switch Camera -->
                    <button 
                        type="button" 
                        @click="switchCamera()" 
                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Kamera</span>
                    </button>

                    <!-- Scan from image file -->
                    <label class="cursor-pointer px-3 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-semibold flex items-center gap-1.5 border border-orange-200 transition-colors">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Pilih Gambar</span>
                        <input type="file" accept="image/*" class="hidden" @change="scanImageFile($event)">
                    </label>
                </div>

                <button 
                    type="button" 
                    @click="closeScanner()" 
                    class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                    Tutup
                </button>
            </div>

            <!-- Manual Input Fallback -->
            <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                <input 
                    type="text" 
                    x-model="manualCode" 
                    @keydown.enter.prevent="submitManualCode()" 
                    placeholder="Ketik kode / SKU manual lalu tekan Enter..." 
                    class="flex-1 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500">
                <button 
                    type="button" 
                    @click="submitManualCode()" 
                    class="px-3.5 py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors">
                    Gunakan
                </button>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes laserSweep {
    0% { transform: translateY(0); }
    50% { transform: translateY(155px); }
    100% { transform: translateY(0); }
}
.animate-laser {
    animation: laserSweep 2s ease-in-out infinite;
}
#barcode-reader video {
    border-radius: 0.75rem;
    object-fit: cover !important;
    width: 100% !important;
}
</style>

<!-- html5-qrcode CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
// Audio Beep generator using Web Audio API
function playScanSuccessBeep() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        const ctx = new AudioContext();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'sine';
        osc.frequency.setValueAtTime(1800, ctx.currentTime);
        gain.gain.setValueAtTime(0.15, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.12);
        osc.start();
        osc.stop(ctx.currentTime + 0.12);
        if (navigator.vibrate) navigator.vibrate(80);
    } catch(e) {
        console.log('Audio feedback not permitted:', e);
    }
}

// Alpine Component for Multi-Engine Barcode & QR Scanner
function barcodeScannerModal() {
    return {
        isOpen: false,
        isScanning: false,
        isProcessingFile: false,
        hasResult: false,
        scannedResult: '',
        scannerTitle: 'Scanner Barcode & QR',
        targetInput: null,
        onScanCallback: null,
        html5QrCode: null,
        currentFacingMode: 'environment',
        errorMessage: '',
        manualCode: '',

        openScanner(opts = {}) {
            this.isOpen = true;
            this.hasResult = false;
            this.scannedResult = '';
            this.errorMessage = '';
            this.manualCode = '';
            this.isProcessingFile = false;
            this.scannerTitle = opts.title || 'Scanner Barcode & QR';
            this.targetInput = opts.targetInput || null;
            this.onScanCallback = opts.callback || null;
            
            this.$nextTick(() => {
                this.startCamera();
            });
        },

        startCamera() {
            const readerEl = document.getElementById('barcode-reader');
            if (!readerEl || typeof Html5Qrcode === 'undefined') {
                this.errorMessage = 'Pustaka scanner belum dimuat. Silakan gunakan Pilih Gambar atau Input Manual.';
                return;
            }

            if (this.html5QrCode) {
                this.stopCamera().then(() => this.initScanner());
            } else {
                this.initScanner();
            }
        },

        initScanner() {
            // Supported formats: 1D Barcodes (Code 128, EAN, UPC) + 2D (QR, Data Matrix)
            const formats = typeof Html5QrcodeSupportedFormats !== 'undefined' ? [
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.CODE_93,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.ITF,
                Html5QrcodeSupportedFormats.DATA_MATRIX,
            ] : undefined;

            this.html5QrCode = new Html5Qrcode("barcode-reader", { formatsToSupport: formats });
            const config = {
                fps: 15,
                qrbox: { width: 260, height: 160 },
                aspectRatio: 1.333334
            };

            this.html5QrCode.start(
                { facingMode: this.currentFacingMode },
                config,
                (decodedText) => {
                    this.onScanSuccess(decodedText);
                },
                (errorMessage) => {
                    // Scanning active, frame-level search
                }
            ).then(() => {
                this.isScanning = true;
                this.errorMessage = '';
            }).catch(err => {
                this.isScanning = false;
                this.errorMessage = 'Kamera tidak aktif atau izin ditolak. Silakan gunakan tombol "Pilih Gambar" untuk membaca barcode dari foto label produk.';
                console.warn('Camera start error:', err);
            });
        },

        stopCamera() {
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                return this.html5QrCode.stop().then(() => {
                    this.html5QrCode.clear();
                    this.isScanning = false;
                }).catch(err => console.log('Error stopping camera:', err));
            }
            return Promise.resolve();
        },

        switchCamera() {
            this.currentFacingMode = (this.currentFacingMode === 'environment') ? 'user' : 'environment';
            this.stopCamera().then(() => {
                this.startCamera();
            });
        },

        /**
         * Multi-Pass Advanced Image Barcode Decoder:
         * 1. Native Hardware BarcodeDetector API (Google Chrome / Edge)
         * 2. Html5Qrcode direct scan
         * 3. Canvas Pre-processing (downscale + contrast + grayscale + invert)
         */
        async scanImageFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.isProcessingFile = true;
            this.errorMessage = '';
            await this.stopCamera();

            try {
                // Pass 1: Native BarcodeDetector API (Fastest & Highest Accuracy on Phone/Camera Photos)
                if ('BarcodeDetector' in window) {
                    try {
                        const detector = new BarcodeDetector({
                            formats: [
                                'code_128', 'code_39', 'code_93', 'ean_13', 'ean_8',
                                'upc_a', 'upc_e', 'itf', 'qr_code', 'data_matrix', 'aztec', 'pdf417'
                            ]
                        });
                        const bitmap = await createImageBitmap(file);
                        const results = await detector.detect(bitmap);
                        if (results && results.length > 0 && results[0].rawValue) {
                            this.isProcessingFile = false;
                            this.onScanSuccess(results[0].rawValue);
                            return;
                        }
                    } catch (e) {
                        console.debug('Native BarcodeDetector pass skipped:', e);
                    }
                }

                // Pass 2: Html5Qrcode Library Scan
                if (!this.html5QrCode) {
                    this.html5QrCode = new Html5Qrcode("barcode-reader");
                }

                try {
                    const decodedText = await this.html5QrCode.scanFile(file, true);
                    if (decodedText) {
                        this.isProcessingFile = false;
                        this.onScanSuccess(decodedText);
                        return;
                    }
                } catch (e) {
                    console.debug('Direct html5QrCode pass did not find barcode, trying canvas preprocessor...');
                }

                // Pass 3: Canvas Enhanced Pre-processing (Grayscale + Contrast Boost)
                const enhancedBlob = await this.preprocessImage(file, 'contrast');
                if (enhancedBlob) {
                    try {
                        const decodedText = await this.html5QrCode.scanFile(enhancedBlob, true);
                        if (decodedText) {
                            this.isProcessingFile = false;
                            this.onScanSuccess(decodedText);
                            return;
                        }
                    } catch (e) {
                        console.debug('Enhanced pass did not find barcode, trying inverted pass...');
                    }
                }

                // Pass 4: Canvas Inverted B&W (for dark backgrounds)
                const invertedBlob = await this.preprocessImage(file, 'invert');
                if (invertedBlob) {
                    try {
                        const decodedText = await this.html5QrCode.scanFile(invertedBlob, true);
                        if (decodedText) {
                            this.isProcessingFile = false;
                            this.onScanSuccess(decodedText);
                            return;
                        }
                    } catch (e) {
                        console.debug('Inverted pass did not find barcode.');
                    }
                }

                // If all passes fail, show friendly inline guidance
                this.isProcessingFile = false;
                this.errorMessage = 'Barcode atau QR code tidak terdeteksi pada gambar ini. Pastikan foto fokus, cukup terang, dan barcode tidak terpotong.';
            } catch (err) {
                this.isProcessingFile = false;
                this.errorMessage = 'Gagal memproses file gambar. Pastikan format file adalah JPG atau PNG yang valid.';
            }
        },

        // Helper to preprocess image on an offscreen canvas
        preprocessImage(file, mode = 'contrast') {
            return new Promise((resolve) => {
                const img = new Image();
                const url = URL.createObjectURL(file);
                img.onload = () => {
                    URL.revokeObjectURL(url);
                    const canvas = document.createElement('canvas');
                    const maxDim = 1200;
                    let width = img.width;
                    let height = img.height;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');

                    if (mode === 'contrast') {
                        ctx.filter = 'contrast(1.6) grayscale(1)';
                    } else if (mode === 'invert') {
                        ctx.filter = 'invert(1) grayscale(1)';
                    }

                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob((blob) => {
                        resolve(blob);
                    }, 'image/png');
                };
                img.onerror = () => resolve(null);
                img.src = url;
            });
        },

        /**
         * Triggered when a code is detected.
         * Shows preview confirmation screen instead of closing immediately.
         */
        onScanSuccess(decodedText) {
            playScanSuccessBeep();
            this.scannedResult = decodedText.trim();
            this.hasResult = true;
            this.stopCamera();
        },

        /**
         * User confirms applying the scanned result.
         */
        applyResult() {
            if (!this.scannedResult) return;
            const code = this.scannedResult.trim();

            if (typeof this.onScanCallback === 'function') {
                this.onScanCallback(code);
            } else if (this.targetInput) {
                const el = typeof this.targetInput === 'string' ? document.querySelector(this.targetInput) : this.targetInput;
                if (el) {
                    el.value = code;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            this.closeScanner();
        },

        /**
         * User wants to scan another item / restart scanning.
         */
        scanAgain() {
            this.hasResult = false;
            this.scannedResult = '';
            this.errorMessage = '';
            this.$nextTick(() => {
                this.startCamera();
            });
        },

        submitManualCode() {
            if (!this.manualCode.trim()) return;
            this.onScanSuccess(this.manualCode.trim());
        },

        closeScanner() {
            this.stopCamera();
            this.isOpen = false;
            this.isScanning = false;
            this.isProcessingFile = false;
            this.hasResult = false;
            this.scannedResult = '';
            this.manualCode = '';
            this.errorMessage = '';
        }
    }
}

// Global helper to trigger barcode scanner from any button
window.triggerBarcodeScan = function(options) {
    window.dispatchEvent(new CustomEvent('open-barcode-scanner', { detail: options }));
};
</script>
