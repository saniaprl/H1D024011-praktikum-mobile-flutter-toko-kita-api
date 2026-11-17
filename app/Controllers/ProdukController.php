<?php

namespace App\Controllers;

use App\Models\MProduk;

class ProdukController extends RestfulController
{
    // CREATE - Tambah produk
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!is_array($data)) {
            return $this->responseHasil(400, false, "JSON tidak valid");
        }

        $validation = service('validation');
        $validation->setRules([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga' => 'required|integer|greater_than_equal_to[0]'
        ]);

        if (!$validation->run($data)) {
            return $this->responseHasil(
                400, false, $validation->getErrors()
            );
        }

        $model = new MProduk();

        $id = $model->insert([
            'kode_produk' => $data['kode_produk'],
            'nama_produk' => $data['nama_produk'],
            'harga' => (int) $data['harga']
        ]);

        return $this->responseHasil(
            200, true, $model->find($id)
        );
    }

    // READ - Menampilkan semua produk
    public function index()
    {
        $model = new MProduk();

        return $this->responseHasil(
            200, true, $model->findAll()
        );
    }

    // READ - Detail produk
    public function detail($id)
    {
        $model = new MProduk();
        $produk = $model->find($id);

        if (!$produk) {
            return $this->responseHasil(
                404, false, "Produk tidak ditemukan"
            );
        }

        return $this->responseHasil(200, true, $produk);
    }

    // UPDATE - Mengubah produk
    public function ubah($id)
    {
        $model = new MProduk();

        if (!$model->find($id)) {
            return $this->responseHasil(
                404, false, "Produk tidak ditemukan"
            );
        }

        $data = $this->request->getJSON(true);

        if (!is_array($data)) {
            return $this->responseHasil(400, false, "JSON tidak valid");
        }

        $validation = service('validation');
        $validation->setRules([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga' => 'required|integer|greater_than_equal_to[0]'
        ]);

        if (!$validation->run($data)) {
            return $this->responseHasil(
                400, false, $validation->getErrors()
            );
        }

        $model->update($id, [
            'kode_produk' => $data['kode_produk'],
            'nama_produk' => $data['nama_produk'],
            'harga' => (int) $data['harga']
        ]);

        return $this->responseHasil(
            200, true, $model->find($id)
        );
    }

    // DELETE - Menghapus produk
    public function hapus($id)
    {
        $model = new MProduk();

        if (!$model->find($id)) {
            return $this->responseHasil(
                404, false, "Produk tidak ditemukan"
            );
        }

        $model->delete($id);

        return $this->responseHasil(200, true, true);
    }
}
