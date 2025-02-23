<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMart - Dashboard Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <div class="container">
        <div class="header">
            <h1 class="title">
                <i class="fas fa-cash-register"></i>
                Dashboard Kasir
            </h1>
            <div class="buttons">
                <button id="refresh-btn" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Refresh Data
                </button>
                <button id="print-btn" class="btn btn-success">
                    <i class="fas fa-receipt"></i> Simpan Struk
                </button>
                <button id="logout-btn" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>

        <div id="receipt" class="receipt">
            <div class="receipt-header">
                <h2 class="text-2xl font-bold">Toko SmartMart</h2>
                <p class="text-sm text-gray-600">Jl. Contoh No. 123, Indonesia</p>
            </div>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="data-body"></tbody>
            </table>

            <div class="text-right font-bold">
                <p>Total Belanja: <span id="total-belanja">Rp 0</span></p>
            </div>

            <div class="receipt-footer">
                <p>Terima Kasih atas Pembelian Anda!</p>
                <p class="text-sm">Semoga hari Anda menyenangkan!</p>
            </div>
        </div>
    </div>

    <script>
        const formatRupiah = (angka) => "Rp " + parseInt(angka).toLocaleString("id-ID");

        const fetchData = async () => {
            try {
                const refreshBtn = document.getElementById("refresh-btn");
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
                refreshBtn.disabled = true;

                const response = await fetch("https://script.google.com/macros/s/AKfycbwOlu8n7CtBs3Gazk611tiV6uzWcs3MQJRHS_eiXCE_4YalIgF8tlk0mblzi-CE_ZlE/exec");
                const data = await response.json();

                if (!data.items || !Array.isArray(data.items)) throw new Error("Format data API salah!");

                const tbody = document.getElementById("data-body");
                tbody.innerHTML = "";
                let totalBelanja = 0;

                data.items.forEach(item => {
                    if (item.nama_produk && item.harga > 0) {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
                            <td>${item.nama_produk}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td>${formatRupiah(item.harga)}</td>
                            <td>${formatRupiah(item.total)}</td>
                        `;
                        tbody.appendChild(tr);
                        totalBelanja += item.total;
                    }
                });

                document.getElementById("total-belanja").innerText = formatRupiah(totalBelanja);
                gsap.from("tr", { opacity: 0, y: 20, duration: 0.5, stagger: 0.1 });

                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
                refreshBtn.disabled = false;
            } catch (error) {
                console.error("Error fetching data:", error);
                document.getElementById("data-body").innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-red-500">
                            <i class="fas fa-exclamation-triangle"></i> Gagal memuat data!
                        </td>
                    </tr>
                `;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
                refreshBtn.disabled = false;
            }
        };

        // Ganti event listener tombol print di dashboard.php
        document.getElementById("print-btn").addEventListener("click", () => {
            window.location.href = "struk.php"; // Arahkan ke halaman struk yang baru
        });

        document.getElementById("logout-btn").addEventListener("click", () => {
            // Animasi sebelum logout
            gsap.to(".container", {
                opacity: 0,
                y: -50,
                duration: 0.5,
                onComplete: () => {
                    window.location.href = "index.php";
                }
            });
        });

        fetchData();
    </script>
</body>

</html>