<div class="modal fade" id="createRetur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Retur Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="formRetur" id="formRetur" action="">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Id Barang</label>
                        <div class="col-sm-6">
                            <input type="number" name="barangId" id="barangId" class="form-control" required>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary mb-3">Confirm</button>
                        </div>
                    </div>
                    <div id="dataBarang" style="display: none;">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Kode</label>
                            <div class="col-sm-9">
                                <input type="text" name="kode" id="kode" class="form-control-plaintext">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" id="nama" class="form-control-plaintext">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Jumlah</label>
                            <div class="col-sm-9">
                                <input type="text" name="jumlah" id="jumlah" class="form-control-plaintext">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Stok</label>
                            <div class="col-sm-9">
                                <input type="text" name="stok" id="stok" class="form-control-plaintext">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Jumlah Retur</label>
                        <div class="col-sm-9">
                            <input type="number" name="jmlretur" id="jmlretur" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="tambahRetur()">Tambah</button>
            </div>
        </div>
    </div>
</div>