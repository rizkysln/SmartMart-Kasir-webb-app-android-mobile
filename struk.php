<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja - SmartMart</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="./styles.css">

</head>

<body>
    <div id="loading-overlay">
        <div class="loader">
            <i class="fas fa-spinner"></i>
            <p>Menyimpan struk...</p>
        </div>
    </div>
    <div id="payment-overlay" class="payment-overlay"></div>
    <div class="struk-container" id="struk">
        <div class="header">
            <div class="logo">SMARTMART</div>
            <div class="info">Jl. Contoh No. 123</div>
            <div class="info">Indonesia</div>
            <div class="info">Telp: (+62) 1234567</div>
            <div class="timestamp">
                <div id="tanggal"></div>
                <div id="waktu"></div>
                <div>Kasir: ADMIN-01</div>
                <div>No. Struk: #RM<span id="noStruk"></span></div>
            </div>
        </div>

        <div id="items-container">
            <!-- Items will be populated by JavaScript -->
        </div>

        <!-- Previous HTML remains the same until total-section -->

        <div class="total-section">
            <div class="total">
                <span>TOTAL</span>
                <span id="total-amount">Rp 0</span>
            </div>
            <div class="payment-details">
                <div class="payment-row">
                    <span>TUNAI</span>
                    <span id="paid-amount">Rp 0</span>
                </div>
                <div class="payment-row">
                    <span>KEMBALI</span>
                    <span id="change-amount">Rp 0</span>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="dotted-line"></div>
            <p>Terima Kasih Atas Kunjungan Anda</p>
            <p>Barang Yang Sudah Dibeli</p>
            <p>Tidak Dapat Dikembalikan</p>
            <div class="dotted-line"></div>
            <p>Semoga Hari Anda Menyenangkan!</p>
        </div>

        <!-- Rest of the HTML remains the same -->
        <div class="buttons no-print">
            <button class="btn btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print Struk
            </button>
            <button class="btn btn-save" onclick="saveAsPNG()">
                <i class="fas fa-download"></i> Simpan PNG
            </button>
            <button class="btn btn-back" onclick="window.location.href='dashboard.php'">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        <div id="payment-dialog" class="payment-dialog">
            <h3>Masukkan Pembayaran</h3>
            <input type="number" id="payment-input" placeholder="Masukkan jumlah uang" />
            <button onclick="processPayment()">Proses Pembayaran</button>
        </div>

        <script>
            const formatRupiah = (angka) => "Rp " + parseInt(angka).toLocaleString("id-ID");

            document.getElementById('noStruk').textContent = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');

            const now = new Date();
            document.getElementById('tanggal').textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('waktu').textContent = now.toLocaleTimeString('id-ID');

            async function saveAsPNG() {
                const loadingOverlay = document.getElementById('loading-overlay');
                loadingOverlay.style.display = 'flex';

                try {
                    const strukElement = document.getElementById('struk');
                    const canvas = await html2canvas(strukElement, {
                        scale: 2, // Higher resolution
                        backgroundColor: '#ffffff',
                        logging: false,
                        useCORS: true
                    });

                    // Get current date for filename
                    const date = new Date();
                    const dateStr = date.toISOString().split('T')[0];
                    const timeStr = date.toTimeString().split(' ')[0].replace(/:/g, '-');
                    const filename = `struk_${dateStr}_${timeStr}.png`;

                    // Create temporary link and trigger download
                    const link = document.createElement('a');
                    link.download = filename;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                } catch (error) {
                    console.error('Error saving receipt:', error);
                    alert('Gagal menyimpan struk sebagai gambar. Silakan coba lagi.');
                } finally {
                    loadingOverlay.style.display = 'none';
                }
            }
            // Previous JavaScript code remains the same

            let totalBelanja = 0;

            const processPayment = () => {
            const paymentInput = document.getElementById('payment-input');
            const payment = parseInt(paymentInput.value);
            
            if (isNaN(payment) || payment < totalBelanja) {
                alert('Jumlah pembayaran tidak valid atau kurang dari total belanja!');
                return;
            }

            const change = payment - totalBelanja;
            
            document.getElementById('paid-amount').textContent = formatRupiah(payment);
            document.getElementById('change-amount').textContent = formatRupiah(change);
            
            // Hide payment dialog and overlay
            document.getElementById('payment-dialog').style.display = 'none';
            document.getElementById('payment-overlay').style.display = 'none';
        };

            const fetchData = async () => {
                try {
                    const response = await fetch("https://script.google.com/macros/s/AKfycbwOlu8n7CtBs3Gazk611tiV6uzWcs3MQJRHS_eiXCE_4YalIgF8tlk0mblzi-CE_ZlE/exec");
                    const data = await response.json();

                    if (!data.items || !Array.isArray(data.items)) throw new Error("Format data API salah!");

                    const itemsContainer = document.getElementById('items-container');
                    totalBelanja = 0;

                    data.items.forEach(item => {
                        if (item.nama_produk && item.harga > 0) {
                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'item';

                            itemDiv.innerHTML = `
                            <div>${item.nama_produk}</div>
                            <div class="item-details">
                                <span>${item.quantity} x ${formatRupiah(item.harga)}</span>
                                <span>${formatRupiah(item.total)}</span>
                            </div>
                            <div class="dotted-line"></div>
                        `;

                            itemsContainer.appendChild(itemDiv);
                            totalBelanja += item.total;
                        }
                    });

                    document.getElementById('total-amount').textContent = formatRupiah(totalBelanja);

                    // Focus on payment input after loading data
                    document.getElementById('payment-input').focus();
                } catch (error) {
                    console.error("Error fetching data:", error);
                    document.getElementById('items-container').innerHTML = `
                    <div class="item">
                        <div style="color: red; text-align: center;">Gagal memuat data!</div>
                    </div>
                `;
                }
            };

            fetchData();
        </script>
</body>

</html>