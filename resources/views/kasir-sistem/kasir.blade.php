<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kasir deris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
    <!-- !!!! copy right derisdev !!!!! -->
    <style>
        .card-shadow {
            box-shadow: 0 6px 18px rgba(20, 20, 20, 0.06);
        }

        body {
            background: rgb(15, 15, 15);
        }
    </style>
</head>

<body class="text-slate-900">
    <div class="min-h-screen p-4 md:p-8">
        <div class="max-w-[1400px] mx-auto">
            {{-- Top bar --}}
            <header class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl text-white font-extrabold">Kasir Warkop deris</h1>
                        <p class="text-sm text-white">Jakpus</p>
                    </div>

                    <div class="flex items-center gap-4">


                        <div class="flex items-center gap-3">
                            <div class="text-sm text-white">Admin<br /><span class="text-xs text-white">Logout</span>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-emerald-200 flex items-center justify-center">MN</div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Layout main: grid -> content + sidebar --}}
            <main class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Left content (menu) --}}
                <section class="lg:col-span-9 space-y-4">

                    {{-- category pills --}}
                    <div class="flex w-full flex-wrap gap-3 justify-between items-center mb-4">
                        <div>
                            <button class="px-4 py-2 rounded-full border border-gray-500 text-white bg-[#232323]"><a
                                    href="{{ route('post.kategori', 'Makanan') }}">Makanan</a></button>
                            <button class="px-4 py-2 rounded-full border border-gray-500 text-white bg-[#232323]"><a
                                    href="{{ route('post.kategori', 'Minuman') }}">Minuman</a></button>
                        </div>
                        <div class="relative">
                            <!-- lihat produk -->
                            <!-- <a class="px-4 py-2 rounded-full text-white border border-gray-500 bg-orange-400" href="{{ route('show.table') }}">lihat Produk</a> -->
                        </div>
                    </div>

                    {{-- hero row cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <a href="{{ route('show.table') }}">
                            <div
                                class="card-shadow text-white text-center rounded-lg shadow-lg overflow-hidden bg-[#232323] p-4">
                                <div class="text-xl font-semibold">Buat Menu Baru</div>
                                <p class="text-sm">Klik disini</p>
                            </div>
                        </a>
                    </div>

                    {{-- product grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        @foreach($kategoriProducts as $post)
                        <div class="card-shadow rounded-lg bg-[#232323] p-4 text-white flex flex-col justify-between">
                            <div>
                                <div
                                    class="h-40 w-full bg-slate-100 rounded-md mb-3 flex items-center justify-center text-slate-400">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full" alt="">
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold">{{ $post->name }}</h3>
                                        <b class="font-semibold">{{ $post->kategori }}</b>
                                        <div class="text-lg font-bold mt-1">Rp.{{ $post->price }}</div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center">Stock</span>
                                        <input value="{{ old('stock', $post->stock) }}" readonly
                                            class="w-12 bg-[#232323] text-center rounded-md border border-slate-200 py-1" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <button class="btn-add px-4 py-2 rounded-md border text-white font-semibold"
                                    data-id="{{ $post->id }}" data-name="{{ $post->name }}"
                                    data-price="{{ $post->price }}">Add
                                    to
                                    Cart</button>
                                <div class="text-xs text-slate-400">50% OFF</div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </section>

                {{-- Right sidebar (order summary) --}}

                <aside class="lg:col-span-3 text-white">
                    <div class="card-shadow bg-[#232323] rounded-lg p-4 flex flex-col h-full">
                        <h2 class="text-xl font-semibold mb-4 text-center">Keranjang</h2>
                        <div id="cart-items" class="flex-grow space-y-4 overflow-y-auto">
                            <!-- Cart items will be dynamically added here via JS -->
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold">Total:</div>
                                <div id="cart-total" class="text-lg font-bold">Rp0</div>
                            </div>
                            <button
                                class="checkout-btn w-full bg-orange-400 text-white py-3 rounded-md font-semibold transition">
                                Checkout
                            </button>
                        </div>
                    </div>
                </aside>
            </main>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function renderCart(cart, total) {
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = '';

            Object.entries(cart).forEach(([id, item]) => {
                const div = document.createElement('div');
                div.className = "flex justify-between items-center border-b pb-2";
                div.dataset.id = id;
                div.innerHTML = `
            <div>
                <div class="font-semibold">${item.name}</div>
                <div class="text-sm text-slate-500 flex items-center gap-2">
                    Qty:
                    <button class="btn-decrease px-2 bg-slate-200 rounded">−</button>
                    <span class="px-2">${item.quantity}</span>
                    <button class="btn-increase px-2 bg-slate-200 rounded">+</button>
                </div>
            </div>
            <div class="text-right font-bold">Rp${item.price * item.quantity}</div>
            <button class="btn-remove text-red-500 ml-4">🗑️</button>
        `;
                cartItems.appendChild(div);
            });

            document.getElementById('cart-total').textContent = 'Rp' + total;
            attachCartEvents();
        }

        function attachCartEvents() {
            document.querySelectorAll('.btn-increase').forEach(btn => {
                btn.onclick = () => updateQty(btn.closest('[data-id]').dataset.id, 'increase');
            });
            document.querySelectorAll('.btn-decrease').forEach(btn => {
                btn.onclick = () => updateQty(btn.closest('[data-id]').dataset.id, 'decrease');
            });
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.onclick = () => removeItem(btn.closest('[data-id]').dataset.id);
            });
        }

        function updateQty(id, action) {
            fetch("{{ route('cart.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id, action })
            })
                .then(res => res.json())
                .then(data => renderCart(data.cart, data.total));
        }

        function removeItem(id) {
            fetch("{{ route('cart.remove') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id })
            })
                .then(res => res.json())
                .then(data => renderCart(data.cart, data.total));
        }

        document.querySelectorAll('.btn-add').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = parseInt(this.dataset.price);

                fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ id, name, price })
                })
                    .then(res => res.json())
                    .then(data => renderCart(data.cart, data.total));
            });
        });

        // popup print sturk
        document.querySelector('.checkout-btn')?.addEventListener('click', function () {
            fetch("{{ route('cart.checkout') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Ambil data struk dari response
                        let receipt = data.receipt;

                        // Format daftar item yang dibeli
                        let itemList = receipt.items.map(item =>
                            `${item.name} x${item.quantity} - Rp${item.subtotal}`
                        ).join('<br/>'); // Gunakan <br/> agar setiap item terpisah dengan baris baru

                        // Format total
                        let totalAmount = `Rp ${receipt.total}`;

                        // Gunakan SweetAlert2 untuk menampilkan struk dalam popup yang lebih keren
                        Swal.fire({
                            title: 'Struk Pembelian',
                            html: `
                    <strong>Order ID:</strong> ${receipt.order_id}<br/>
                    <strong>Tanggal:</strong> ${receipt.tanggal}<br/><br/>
                    <strong>Item yang dibeli:</strong><br/>
                    ${itemList}<br/><br/>
                    <strong>Total:</strong> ${totalAmount}
                `,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Tutup',
                            cancelButtonText: 'Print',
                            cancelButtonColor: '#3085d6', // Warna tombol Print
                            preConfirm: () => {
                                // Fungsi untuk print saat tombol Print ditekan
                                printReceipt(receipt);
                            }
                        });

                        // Setelah checkout berhasil, kosongkan cart
                        renderCart({}, 0);
                    } else {
                        Swal.fire({
                            title: 'Checkout Gagal',
                            text: data.message || 'Terjadi kesalahan saat checkout.',
                            icon: 'error',
                            confirmButtonText: 'Tutup'
                        });
                    }
                })
                .catch(err => {
                    console.error('Checkout error:', err);
                    Swal.fire({
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi kesalahan saat proses checkout.',
                        icon: 'error',
                        confirmButtonText: 'Tutup'
                    });
                });
        });

        // Fungsi untuk mencetak struk
        function printReceipt(receipt) {
            // Membuat konten HTML untuk struk
            let printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 400px; margin: auto; border: 1px solid #ddd;">
            <h3 style="text-align: center;">Struk Pembelian</h3>
            <p><strong>Order ID:</strong> ${receipt.order_id}</p>
            <p><strong>Tanggal:</strong> ${receipt.tanggal}</p>
            <p><strong>Item yang dibeli:</strong></p>
            <ul>
                ${receipt.items.map(item => `
                    <li>${item.name} x${item.quantity} - Rp${item.subtotal}</li>
                `).join('')}
            </ul>
            <p><strong>Total:</strong> ${receipt.total}</p>
        </div>
    `;

            // Membuka jendela baru dan menulis konten untuk dicetak
            let printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Struk Pembelian</title></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close(); // Close the document to trigger printing
            printWindow.print(); // Trigger the print dialog
        }
    </script>

    <!-- <script src="{{ asset('kasir-js/kasir.js') }}"></script> -->
</body>

</html>