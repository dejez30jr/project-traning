<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit produk kasir deris</title>
    <script src="https://cdn.tailwindcss.com"></script>
        <!-- !!!! copy right derisdev !!!!! -->
</head>
<style>
  body{
            background: rgb(15, 15, 15);
        }
</style>
<body>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-[#232323] text-white w-full max-w-md rounded-xl shadow-xl p-6 relative border border-gray-700">
    <h3 class="text-xl font-bold mb-4">Edit Produk</h3>

    <form action="{{ route('update.produk', $data_old->id) }}" method="post" enctype="multipart/form-data" class="space-y-4">
      @csrf
      @method('PUT')

      <!-- Nama Barang -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Nama Barang</label>
        <input type="text" name="name" value="{{ old('name', $data_old->name) }}"
          class="w-full bg-transparent border border-gray-600 text-white rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400"
          placeholder="Masukkan nama barang" required />
      </div>

      <!-- Kategori -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $data_old->kategori) }}"
          class="w-full bg-transparent border border-gray-600 text-white rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400"
          placeholder="Contoh: Minuman" required />
      </div>

      <!-- Harga -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Harga</label>
        <input type="number" name="price" value="{{ old('price', $data_old->price) }}"
          class="w-full bg-transparent border border-gray-600 text-white rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400"
          placeholder="Rp..." required />
      </div>

      <!-- Gambar -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Gambar</label>
        <input type="file" name="image"
          class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
      </div>

      <!-- Stok -->
      <div>
        <label class="block text-sm font-medium text-gray-300">Stok</label>
        <input type="number" name="stock" value="{{ old('stock', $data_old->stock) }}"
          class="w-full bg-transparent border border-gray-600 text-white rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400"
          placeholder="Jumlah stok" required />
      </div>

      <!-- Tombol -->
      <div class="flex justify-end gap-3 pt-4">
        <button type="button"
          class="px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:bg-gray-700 transition">
          <a href="/kasir-table">Batal</a>
        </button>
        <button type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>